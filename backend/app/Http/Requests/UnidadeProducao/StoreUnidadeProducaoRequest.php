<?php

namespace App\Http\Requests\UnidadeProducao;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnidadeProducaoRequest extends FormRequest
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
            'nome_cultura' => 'required|string|max:255',
            'area_total_ha' => 'required|numeric|min:0',
            'coordenadas_geograficas' => 'nullable|array',
            'propriedade_id' => 'required|exists:propriedades,id',
        ];
    }
}
