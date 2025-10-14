<?php

namespace App\Http\Requests\Propriedade;

use Illuminate\Foundation\Http\FormRequest;

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
}
