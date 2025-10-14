<?php

namespace App\Http\Resources\Produtor;

use App\Http\Resources\Propriedade\PropriedadeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cpf_cnpj' => $this->cpf_cnpj,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'endereco' => $this->endereco,
            'data_cadastro' => $this->data_cadastro,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'propriedades' => PropriedadeResource::collection($this->whenLoaded('propriedades')),
        ];
    }
}
