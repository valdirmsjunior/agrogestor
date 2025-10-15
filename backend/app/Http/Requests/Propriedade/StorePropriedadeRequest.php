<?php

namespace App\Http\Requests\Propriedade;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePropriedadeRequest extends FormRequest
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
            'municipio' => 'required|string|max:100',
            'uf' => 'required|string|size:2',
            'inscricao_estadual' => 'nullable|string|max:20',
            'area_total' => 'required|numeric|min:0',
            'produtor_id' => 'required|exists:produtores,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'municipio.required' => 'O campo município é obrigatório.',
            'uf.required' => 'O campo UF é obrigatório.',
            'uf.size' => 'A UF deve ter 2 caracteres.',
            'area_total.required' => 'O campo área total é obrigatório.',
            'area_total.numeric' => 'A área total deve ser um número.',
            'area_total.min' => 'A área total deve ser maior ou igual a 0.',
            'produtor_id.required' => 'O produtor é obrigatório.',
            'produtor_id.exists' => 'O produtor informado não existe.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }

}
