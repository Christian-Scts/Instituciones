<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'rfc' => ['required', 'string', 'min:12', 'max:13', 'unique:empresas,rfc'],
        'razon_social' => ['required', 'string', 'max:255'],
        'nombre_comercial' => ['nullable', 'string', 'max:255'],
        'giro' => ['nullable', 'string', 'max:255'],
        'ambiente' => ['required', 'in:sandbox,productivo'],
        'ip_registrada' => ['nullable', 'ip'],
        'url_base_api' => ['required', 'url', 'max:255'],
        'endpoint_user' => ['nullable', 'string', 'max:100'],
        'endpoint_password' => ['required', 'string', 'min:8', 'max:255'],
        'pui_user' => ['nullable', 'string', 'max:100'],
        'pui_password' => ['nullable', 'string', 'min:8', 'max:255'],
        'jwt_secret' => ['required', 'string', 'min:16', 'max:255'],
        'ip_whitelist_text' => ['nullable', 'string', 'max:5000'],
        'rate_limit_per_minute' => ['nullable', 'integer', 'min:1', 'max:1000'],
    ];
}
}
