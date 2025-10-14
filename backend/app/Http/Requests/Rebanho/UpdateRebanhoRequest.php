<?php

namespace App\Http\Requests\Rebanho;

use App\Models\Rebanho;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRebanhoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'especie' => ['sometimes', 'required', Rule::in(Rebanho::ESPECIES_PERMITIDAS)],
            'quantidade' => 'sometimes|required|integer|min:1',
            'finalidade' => 'sometimes|required|string|max:255',
            'data_atualizacao' => 'nullable|date',
            'propriedade_id' => 'sometimes|required|exists:propriedades,id',
        ];
    }

    public function messages(): array
    {
        return [
            'especie.in' => 'A espécie deve ser uma das seguintes: ' . implode(', ', Rebanho::ESPECIES_PERMITIDAS),
        ];
    }
}
