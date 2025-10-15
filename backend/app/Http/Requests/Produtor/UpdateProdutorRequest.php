<?php

namespace App\Http\Requests\Produtor;

use App\Models\Produtor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

        if (!$produtor || !Produtor::where('id', $produtor)->exists()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Produtor não encontrado.'
                ], 404)
            );
        }

        return [
            'nome' => 'sometimes|required|string|max:255',
            'cpf_cnpj' => 'sometimes|required|string|unique:produtores,cpf_cnpj,' . $produtor,
            'telefone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|unique:produtores,email,' . $produtor,
            'endereco' => 'sometimes|required|string',
            'data_cadastro' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'cpf_cnpj.unique' => 'CPF/CNPJ já está em uso.',
            'email.unique' => 'E-mail já está em uso.',
            'nome.required' => 'O campo nome é obrigatório.',
            'cpf_cnpj.required' => 'O campo CPF/CNPJ é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado é inválido.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'endereco.required' => 'O campo endereço é obrigatório.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
