<?php

namespace App\Jobs;

use App\Models\EmpresaCliente;
use App\Models\EmpresaClienteImportacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ImportEmpresaClientesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $importacionId)
    {
    }

    public function handle(): void
    {
        $importacion = EmpresaClienteImportacion::findOrFail($this->importacionId);

        $importacion->update([
            'estatus' => 'procesando',
            'iniciado_en' => now(),
            'mensaje_error' => null,
        ]);

        $errores = [];
        $filasOk = 0;
        $filasError = 0;
        $totalFilas = 0;

        try {
            if (!Storage::exists($importacion->archivo_path)) {
                throw new \RuntimeException('El archivo de importación no existe en storage: ' . $importacion->archivo_path);
            }

            $fullPath = Storage::path($importacion->archivo_path);
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            Log::info('ImportEmpresaClientesJob iniciado', [
                'importacion_id' => $importacion->id,
                'empresa_id' => $importacion->empresa_id,
                'archivo_path' => $importacion->archivo_path,
                'full_path' => $fullPath,
                'extension' => $extension,
            ]);

            $rows = [];

            if (in_array($extension, ['csv', 'txt'], true)) {
                if (!class_exists(Reader::class)) {
                    throw new \RuntimeException('La librería league/csv no está instalada.');
                }

                $csv = Reader::createFromPath($fullPath, 'r');
                $csv->setHeaderOffset(0);
                $rows = iterator_to_array($csv->getRecords());
            } elseif (in_array($extension, ['xlsx', 'xls'], true)) {
                if (!class_exists(IOFactory::class)) {
                    throw new \RuntimeException('La librería phpoffice/phpspreadsheet no está instalada.');
                }

                $spreadsheet = IOFactory::load($fullPath);
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $headers = [];

                foreach ($sheet as $index => $line) {
                    if ($index === 1) {
                        $headers = array_map(fn ($v) => trim((string) $v), array_values($line));

                        if (empty(array_filter($headers))) {
                            throw new \RuntimeException('El archivo Excel no contiene encabezados válidos en la primera fila.');
                        }

                        continue;
                    }

                    $assoc = [];
                    foreach (array_values($line) as $i => $value) {
                        $assoc[$headers[$i] ?? "col_{$i}"] = $value;
                    }

                    $rows[] = $assoc;
                }
            } else {
                throw new \RuntimeException('Formato de archivo no soportado: ' . $extension);
            }

            if (empty($rows)) {
                throw new \RuntimeException('El archivo no contiene filas de datos.');
            }

            foreach ($rows as $i => $row) {
                $numeroFila = $i + 2;
                $totalFilas++;

                $normalizada = $this->normalizarFila($row, $importacion->empresa_id);

                $validator = Validator::make($normalizada, $this->rules(), $this->messages());

                if ($validator->fails()) {
                    $filasError++;
                    $errores[] = [
                        'fila' => $numeroFila,
                        'curp' => $normalizada['curp'] ?? null,
                        'errores' => $validator->errors()->all(),
                    ];
                    continue;
                }

                try {
                    $payload = $validator->validated();

                    if ($importacion->sobrescribir) {
                        EmpresaCliente::updateOrCreate(
                            [
                                'empresa_id' => $payload['empresa_id'],
                                'curp' => $payload['curp'],
                                'fecha_referencia' => $payload['fecha_referencia'],
                            ],
                            $payload
                        );
                    } else {
                        EmpresaCliente::create($payload);
                    }

                    $filasOk++;
                } catch (Throwable $e) {
                    $filasError++;
                    $errores[] = [
                        'fila' => $numeroFila,
                        'curp' => $normalizada['curp'] ?? null,
                        'errores' => ['Error al guardar: ' . $e->getMessage()],
                    ];
                }
            }

            $importacion->update([
                'total_filas' => $totalFilas,
                'filas_ok' => $filasOk,
                'filas_error' => $filasError,
                'errores_json' => $errores,
                'estatus' => $filasError > 0 ? 'completada_con_errores' : 'completada',
                'terminado_en' => now(),
            ]);

            Log::info('ImportEmpresaClientesJob completado', [
                'importacion_id' => $importacion->id,
                'total_filas' => $totalFilas,
                'filas_ok' => $filasOk,
                'filas_error' => $filasError,
            ]);
        } catch (Throwable $e) {
            Log::error('ImportEmpresaClientesJob error', [
                'importacion_id' => $importacion->id ?? null,
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $importacion->update([
                'estatus' => 'error',
                'mensaje_error' => $e->getMessage(),
                'terminado_en' => now(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        $importacion = EmpresaClienteImportacion::find($this->importacionId);

        if ($importacion) {
            $importacion->update([
                'estatus' => 'error',
                'mensaje_error' => $e->getMessage(),
                'terminado_en' => now(),
            ]);
        }

        Log::error('ImportEmpresaClientesJob failed()', [
            'importacion_id' => $this->importacionId,
            'mensaje' => $e->getMessage(),
        ]);
    }

    private function normalizarFila(array $row, int $empresaId): array
    {
        $curp = strtoupper(trim((string) ($row['curp'] ?? '')));
        $sexo = strtoupper(trim((string) ($row['sexo_asignado'] ?? '')));
        $correo = trim((string) ($row['correo'] ?? ''));
        $cp = preg_replace('/\D/', '', (string) ($row['codigo_postal'] ?? ''));
        $telefono = preg_replace('/[^\d\+]/', '', (string) ($row['telefono'] ?? ''));

        return [
            'empresa_id' => $empresaId,
            'curp' => $curp,
            'nombre' => $this->nullableTrim($row['nombre'] ?? null),
            'primer_apellido' => $this->nullableTrim($row['primer_apellido'] ?? null),
            'segundo_apellido' => $this->nullableTrim($row['segundo_apellido'] ?? null),
            'fecha_nacimiento' => $this->nullableDate($row['fecha_nacimiento'] ?? null),
            'lugar_nacimiento' => $this->nullableTrim($row['lugar_nacimiento'] ?? null),
            'sexo_asignado' => $sexo !== '' ? $sexo : null,
            'telefono' => $telefono !== '' ? $telefono : null,
            'correo' => $correo !== '' ? $correo : null,
            'direccion' => $this->nullableTrim($row['direccion'] ?? null),
            'calle' => $this->nullableTrim($row['calle'] ?? null),
            'numero' => $this->nullableTrim($row['numero'] ?? null),
            'colonia' => $this->nullableTrim($row['colonia'] ?? null),
            'codigo_postal' => $cp !== '' ? $cp : null,
            'municipio_o_alcaldia' => $this->nullableTrim($row['municipio_o_alcaldia'] ?? null),
            'entidad_federativa' => $this->nullableTrim($row['entidad_federativa'] ?? null),
            'tipo_evento' => $this->nullableTrim($row['tipo_evento'] ?? null),
            'fecha_evento' => $this->nullableDate($row['fecha_evento'] ?? null),
            'descripcion_lugar_evento' => $this->nullableTrim($row['descripcion_lugar_evento'] ?? null),
            'fecha_referencia' => $this->nullableDate($row['fecha_referencia'] ?? now()->toDateString()),
        ];
    }

    private function rules(): array
    {
        return [
            'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/'],
            'nombre' => ['nullable', 'string', 'max:50'],
            'primer_apellido' => ['nullable', 'string', 'max:50'],
            'segundo_apellido' => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:20'],
            'sexo_asignado' => ['nullable', 'regex:/^[MHX]{1}$/'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'correo' => ['nullable', 'email', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'calle' => ['nullable', 'string', 'max:50'],
            'numero' => ['nullable', 'string', 'max:20'],
            'colonia' => ['nullable', 'string', 'max:50'],
            'codigo_postal' => ['nullable', 'regex:/^\d{5}$/'],
            'municipio_o_alcaldia' => ['nullable', 'string', 'max:100'],
            'entidad_federativa' => ['nullable', 'string', 'max:40'],
            'tipo_evento' => ['nullable', 'string', 'max:500'],
            'fecha_evento' => ['nullable', 'date'],
            'descripcion_lugar_evento' => ['nullable', 'string', 'max:500'],
            'fecha_referencia' => ['required', 'date'],
        ];
    }

    private function messages(): array
    {
        return [
            'curp.required' => 'La CURP es obligatoria.',
            'curp.size' => 'La CURP debe tener 18 caracteres.',
            'curp.regex' => 'La CURP debe contener solo mayúsculas y números.',
            'sexo_asignado.regex' => 'El sexo asignado debe ser M, H o X.',
            'correo.email' => 'El correo no tiene un formato válido.',
            'codigo_postal.regex' => 'El código postal debe tener 5 dígitos.',
            'fecha_referencia.required' => 'La fecha_referencia es obligatoria.',
        ];
    }

    private function nullableTrim(mixed $value): ?string
    {
        $value = trim((string) $value);
        return $value !== '' ? $value : null;
    }

    private function nullableDate(mixed $value): ?string
    {
        $value = trim((string) $value);
        return $value !== '' ? $value : null;
    }
}