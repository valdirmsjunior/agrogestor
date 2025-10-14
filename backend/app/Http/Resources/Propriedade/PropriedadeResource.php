<?php

namespace App\Http\Resources\Propriedade;

use App\Http\Resources\Produtor\ProdutorResource;
use App\Http\Resources\Rebanho\RebanhoResource;
use App\Http\Resources\UnidadeProducao\UnidadeProducaoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropriedadeResource extends JsonResource
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
            'municipio' => $this->municipio,
            'uf' => $this->uf,
            'inscricao_estadual' => $this->inscricao_estadual,
            'area_total' => (float) $this->area_total,
            'produtor_id' => $this->produtor_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'produtor' => new ProdutorResource($this->whenLoaded('produtor')),
            'unidades_producao' => UnidadeProducaoResource::collection($this->whenLoaded('unidadesProducao')),
            'rebanhos' => RebanhoResource::collection($this->whenLoaded('rebanhos')),
        ];
    }
}
