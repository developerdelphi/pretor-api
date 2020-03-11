<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityRequest extends FormRequest
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
            $ruleName = 'required|string|min:3|max:50|unique:entities,name';

        if (($this->method() == 'PATH') || ($this->method() == 'PUT'))
            $ruleName = 'required|string|min:3|max:50|unique:entities,name,' . $this->entity->id;

        return ['name' => $ruleName];
    }

    public function attributes()
    {
        return ['name' => 'Nome'];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute não informado',
            'name.unique' => ':attribute de Entidade informado está em uso',
            'name.string' => 'Tipo de dado para :attribute é inválido',
            'name.min' => 'A informação para :attribute parece ser muito pequena',
            'name.max' => 'A informação para :attribute é maior que 50 caracteres',
        ];
    }
}
