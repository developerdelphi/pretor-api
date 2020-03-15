<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PersonaRequest extends FormRequest
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
        /*
        if ($this->method() == 'POST')
            $ruleName = 'required|string|min:3|max:50|unique:kinds,name';

        if (($this->method() == 'PATH') || ($this->method() == 'PUT'))
            $ruleName = 'required|string|min:3|max:50|unique:kinds,name,' . $this->kind->id;
        */
        return [
            'name' => 'required|string|min:3|max:50',
            'qualifications.*.cpf' => [
                'filled',
                function ($attribute, $value, $fail) {
                    $cpfExist = DB::table('personas')
                        ->where('qualifications->docs->cpf', $value)
                        ->first();
                    if ($cpfExist) {
                        if (($this->method() == 'PATH') || ($this->method() == 'PUT')) {
                            if ($this->persona->id != $cpfExist->id)
                                $fail('CPF cadastrado no sistema.');
                        }
                        $fail('CPF cadastrado no sistema.');
                    }
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'qualifications.*.cpf' => 'CPF'
        ];
    }

    public function messages()
    {
        return [
            'qualifications.*.cpf.required' => ':attribute não informado',
            'qualifications.*.cpf.filled' => ':attribute é obrigatório',
            'name.required' => ':attribute não informado',
            'name.string' => 'Tipo de dado para :attribute é inválido',
            'name.min' => 'A informação para :attribute parece ser muito pequena',
            'name.max' => 'A informação para :attribute é maior que 50 caracteres',
        ];
    }
}
