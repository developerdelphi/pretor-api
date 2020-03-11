<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        if ($this->method() == 'POST')
            $ruleName = 'required|string|min:3|max:50|unique:areas,name';

        if (($this->method() == 'PATH') || ($this->method() == 'PUT'))
            $ruleName = 'required|string|min:3|max:50|unique:areas,name,' . $this->area->id;

        return [
            'name' => $ruleName,
            'origin' => [
                'required',
                'string',
                Rule::in(['Judicial', 'Administrativo']),
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'origin' => 'Origem do processo'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute não informado',
            'name.unique' => ':attribute de Área informado está em uso',
            'name.string' => 'Tipo de dado para :attribute é inválido',
            'name.min' => 'A informação para :attribute parece ser muito pequena',
            'name.max' => 'A informação para :attribute é maior que 50 caracteres',
            'origin.required' => ':attribute não informado',
            'origin.in' => ':attribute deve ser Judicial ou Administrativo'
        ];
    }
}
