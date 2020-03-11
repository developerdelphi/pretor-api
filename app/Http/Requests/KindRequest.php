<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KindRequest extends FormRequest
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
            $ruleName = 'required|string|min:3|max:50|unique:kinds,name';

        if (($this->method() == 'PATH') || ($this->method() == 'PUT'))
            $ruleName = 'required|string|min:3|max:50|unique:kinds,name,' . $this->kind->id;

        return [
            'name' => $ruleName,
            'area_id' => 'required|exists:areas,id'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'area_id' => 'Área Jurídica'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute não informado',
            'name.unique' => ':attribute de Entidade informado está em uso',
            'name.string' => 'Tipo de dado para :attribute é inválido',
            'name.min' => 'A informação para :attribute parece ser muito pequena',
            'name.max' => 'A informação para :attribute é maior que 50 caracteres',
            'area_id.required' => ':attribute não informada',
            'area_id.exists' => ':attribute não não localizada nos registros',
        ];
    }
}
