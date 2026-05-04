<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginPuiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institucion_id' => 'required|string|min:4|max:13',
            'clave' => 'required|string|min:1|max:50',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'institucion_id' => strtoupper(trim((string) $this->institucion_id)),
        ]);
    }
}
