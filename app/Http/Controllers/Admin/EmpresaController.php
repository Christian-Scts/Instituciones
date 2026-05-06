<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaStoreRequest;
use App\Http\Requests\EmpresaUpdateRequest;
use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::latest()->paginate(20);
        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

public function store(EmpresaStoreRequest $request)
{
    $data = $request->validated();

    $plainPassword = $data['endpoint_password'];

    $whitelistText = $request->input('ip_whitelist_text', '');
    $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));
    $ips = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP)));

    $empresaData = [
        'rfc' => strtoupper(trim($data['rfc'])),
        'razon_social' => trim($data['razon_social']),
        'nombre_comercial' => $data['nombre_comercial'] ?? null,
        'slug' => Str::slug($data['razon_social'] . '-' . $data['rfc']),
        'giro' => $data['giro'] ?? null,
        'ambiente' => $data['ambiente'],
        'ip_registrada' => $data['ip_registrada'] ?? null,
        'url_base_api' => rtrim($data['url_base_api'], '/'),
        'ip_whitelist' => $ips,
        'rate_limit_per_minute' => (int) ($data['rate_limit_per_minute'] ?? 60),

        // Credenciales institucionales internas
        'endpoint_user' => $data['endpoint_user'] ?? 'PUI',
        'endpoint_password_hash' => Hash::make($plainPassword),
        'endpoint_password_encrypted' => Crypt::encryptString($plainPassword),

        // Credenciales PUI externa
        'pui_user' => $data['pui_user'] ?? 'PUI',
        'pui_password_encrypted' => Crypt::encryptString($data['pui_password'] ?? $plainPassword),

        'jwt_secret_encrypted' => Crypt::encryptString($data['jwt_secret']),
        'jwt_algo' => $data['jwt_algo'] ?? 'HS256',

        'activo' => true,
        'aprobado_sandbox' => false,
        'aprobado_productivo' => false,
    ];

    Empresa::create($empresaData);

    return redirect()
        ->route('admin.empresas.index')
        ->with('success', 'Empresa registrada correctamente.');
}

    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

public function update(EmpresaUpdateRequest $request, Empresa $empresa)
{
    $data = $request->validated();

    $whitelistText = $request->input('ip_whitelist_text', '');
    $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));
    $ips = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP)));

    $update = [
        'rfc' => strtoupper(trim($data['rfc'])),
        'razon_social' => trim($data['razon_social']),
        'nombre_comercial' => $data['nombre_comercial'] ?? null,
        'slug' => Str::slug($data['razon_social'] . '-' . $data['rfc']),
        'giro' => $data['giro'] ?? null,
        'ambiente' => $data['ambiente'],
        'ip_registrada' => $data['ip_registrada'] ?? null,
        'url_base_api' => rtrim($data['url_base_api'], '/'),
        'ip_whitelist' => $ips,
        'rate_limit_per_minute' => (int) ($data['rate_limit_per_minute'] ?? 60),

        'endpoint_user' => $data['endpoint_user'] ?? $empresa->endpoint_user ?? 'PUI',
        'pui_user' => $data['pui_user'] ?? $empresa->pui_user ?? 'PUI',
        'jwt_algo' => $data['jwt_algo'] ?? $empresa->jwt_algo ?? 'HS256',

        'activo' => $request->boolean('activo'),
        'aprobado_sandbox' => $request->boolean('aprobado_sandbox'),
        'aprobado_productivo' => $request->boolean('aprobado_productivo'),
    ];

    // Si cambia la contraseña institucional, actualiza hash y cifrado reversible
    if (!empty($data['endpoint_password'])) {
        $update['endpoint_password_hash'] = Hash::make($data['endpoint_password']);
        $update['endpoint_password_encrypted'] = Crypt::encryptString($data['endpoint_password']);
    }

    // Si cambia la contraseña PUI externa
    if (!empty($data['pui_password'])) {
        $update['pui_password_encrypted'] = Crypt::encryptString($data['pui_password']);
    }

    // Si cambia JWT secret
    if (!empty($data['jwt_secret'])) {
        $update['jwt_secret_encrypted'] = Crypt::encryptString($data['jwt_secret']);
    }

    $empresa->update($update);

    return redirect()
        ->route('admin.empresas.index')
        ->with('success', 'Empresa actualizada correctamente.');
}
}