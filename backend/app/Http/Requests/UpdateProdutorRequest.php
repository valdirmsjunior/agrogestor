<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdutorRequest extends FormRequest
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
        $produtor = $this->route('produtor');
        return [
            'nome' => 'sometimes|required|string|max:255',
            'cpf_cnpj' => 'sometimes|required|string|unique:produtores,cpf_cnpj,' . $produtor->id,
            'telefone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|unique:produtores,email,' . $produtor->id,
            'endereco' => 'sometimes|required|string',
            'data_cadastro' => 'nullable|date',
        ];
    }
}
