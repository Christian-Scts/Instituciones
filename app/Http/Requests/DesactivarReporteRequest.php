<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesactivarReporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [
        'id' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-z0-9\-]+$/'
        ],
        'curp' => [
            'required',
            'string',
            'size:18',
            'regex:/^[A-Z0-9]+$/'
        ],
    ];
}

    protected function prepareForValidation()
{
    $this->merge([
        'id' => preg_replace('/[^A-Za-z0-9\-]/', '', $this->id),
        'curp' => preg_replace('/[^A-Z0-9]/', '', strtoupper($this->curp)),
    ]);
}
public function withValidator($validator)
{
    $validator->after(function ($validator) {

        $fields = ['id', 'curp'];

        foreach ($fields as $field) {
            $value = $this->input($field);

            if (
                str_contains($value, '--') ||
                str_contains($value, ' ') ||
                str_contains(strtolower($value), 'or') ||
                str_contains(strtolower($value), 'and')
            ) {
                $validator->errors()->add($field, 'Formato inválido');
            }
        }
    });
}
}