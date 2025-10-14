<?php

namespace App\Http\Requests\Propriedade;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropriedadeRequest extends FormRequest
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
        $propriedade = $this->route('propriedade');
        return [
            'nome' => 'sometimes|required|string|max:255',
            'municipio' => 'sometimes|required|string|max:100',
            'uf' => 'sometimes|required|string|size:2',
            'inscricao_estadual' => 'nullable|string|max:20',
            'area_total' => 'sometimes|required|numeric|min:0',
            'produtor_id' => 'sometimes|required|exists:produtores,id',
        ];
    }
}
