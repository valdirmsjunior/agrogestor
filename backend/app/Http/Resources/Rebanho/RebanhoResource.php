<?php

namespace App\Http\Resources\Rebanho;

use App\Http\Resources\Propriedade\PropriedadeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RebanhoResource extends JsonResource
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
            'especie' => $this->especie,
            'quantidade' => $this->quantidade,
            'finalidade' => $this->finalidade,
            'data_atualizacao' => $this->data_atualizacao,
            'propriedade_id' => $this->propriedade_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'propriedade' => new PropriedadeResource($this->whenLoaded('propriedade')),
        ];
    }
}
