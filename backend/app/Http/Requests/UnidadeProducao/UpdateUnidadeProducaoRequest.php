<?php

namespace App\Http\Requests\UnidadeProducao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUnidadeProducaoRequest extends FormRequest
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
            'nome_cultura' => 'sometimes|required|string|max:255',
            'area_total_ha' => 'sometimes|required|numeric|min:0',
            'coordenadas_geograficas' => 'nullable|array',
            'propriedade_id' => 'sometimes|required|exists:propriedades,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome_cultura.required' => 'O campo cultura é obrigatório.',
            'area_total_ha.required' => 'O campo área total (ha) é obrigatório.',
            'area_total_ha.numeric' => 'A área total deve ser um número.',
            'area_total_ha.min' => 'A área total deve ser maior ou igual a 0.',
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
