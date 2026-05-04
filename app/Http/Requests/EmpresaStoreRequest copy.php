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
            'rfc' => 'required|string|min:12|max:13|unique:empresas,rfc',
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'ambiente' => 'required|in:sandbox,productivo',
            'ip_registrada' => 'nullable|string|max:45',
            'url_base_api' => 'nullable|url',
            'endpoint_user' => 'nullable|string|max:100',
            'endpoint_password' => 'required|string|min:8|max:255',
            'pui_user' => 'nullable|string|max:100',
            'pui_password' => 'nullable|string|max:255',
            'jwt_secret' => 'required|string|min:16',
        ];
    }
}
