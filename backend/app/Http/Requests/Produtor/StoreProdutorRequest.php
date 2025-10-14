<?php

namespace App\Http\Requests\Produtor;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|unique:produtores,cpf_cnpj',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|unique:produtores,email',
            'endereco' => 'required|string',
            'data_cadastro' => 'nullable|date',
        ];
    }
}
