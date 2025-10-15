<?php

namespace App\Http\Requests\Rebanho;

use App\Models\Rebanho;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreRebanhoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'especie' => ['required', Rule::in(Rebanho::ESPECIES_PERMITIDAS)],
            'quantidade' => 'required|integer|min:1',
            'finalidade' => 'required|string|max:255',
            'data_atualizacao' => 'nullable|date',
            'propriedade_id' => 'required|exists:propriedades,id',
        ];
    }

    public function messages(): array
    {
        return [
            'especie.required' => 'A espécie é obrigatória.',
            'especie.in' => 'Espécie inválida. Escolha entre: ' . implode(', ', Rebanho::ESPECIES_PERMITIDAS),
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade deve ser pelo menos 1.',
            'finalidade.required' => 'A finalidade é obrigatória.',
            'propriedade_id.required' => 'A propriedade é obrigatória.',
            'propriedade_id.exists' => 'A propriedade informada não existe.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
