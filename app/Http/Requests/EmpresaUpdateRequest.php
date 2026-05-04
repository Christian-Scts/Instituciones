<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('rfc')) {
            $this->merge([
                'rfc' => strtoupper(trim((string) $this->input('rfc'))),
            ]);
        }

        if ($this->has('url_base_api')) {
            $this->merge([
                'url_base_api' => rtrim(trim((string) $this->input('url_base_api')), '/'),
            ]);
        }
    }

    public function rules(): array
    {
        $empresaId = $this->route('empresa')?->id;

        return [
            'rfc' => [
                'required',
                'string',
                'min:12',
                'max:13',
                Rule::unique('empresas', 'rfc')->ignore($empresaId),
            ],
            'razon_social' => ['required', 'string', 'max:255'],
            'nombre_comercial' => ['nullable', 'string', 'max:255'],
            'giro' => ['nullable', 'string', 'max:255'],
            'ambiente' => ['required', 'in:sandbox,productivo'],
            'ip_registrada' => ['nullable', 'ip'],
            'url_base_api' => ['required', 'url', 'max:255'],

            'endpoint_user' => ['nullable', 'string', 'max:100'],
            'endpoint_password' => ['nullable', 'string', 'min:8', 'max:255'],

            'pui_user' => ['nullable', 'string', 'max:100'],
            'pui_password' => ['nullable', 'string', 'min:8', 'max:255'],

            'jwt_secret' => ['nullable', 'string', 'min:16', 'max:255'],
            'jwt_algo' => ['nullable', 'in:HS256,HS384,HS512'],

            'ip_whitelist_text' => ['nullable', 'string', 'max:5000'],
            'rate_limit_per_minute' => ['nullable', 'integer', 'min:1', 'max:1000'],

            'activo' => ['nullable', 'boolean'],
            'aprobado_sandbox' => ['nullable', 'boolean'],
            'aprobado_productivo' => ['nullable', 'boolean'],
        ];
    }
}