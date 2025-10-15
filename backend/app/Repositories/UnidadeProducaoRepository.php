<?php

namespace App\Repositories;

use App\Models\UnidadeProducao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UnidadeProducaoRepository
{
    public function __construct(protected UnidadeProducao $unidadeProducao)
    {
        $this->unidadeProducao = $unidadeProducao;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->unidadeProducao->with('propriedade')->paginate($perPage);
    }

    public function find(int $id): ?UnidadeProducao
    {
        return $this->unidadeProducao->with('propriedade')->find($id);
    }

    public function create(array $data): UnidadeProducao
    {
        return $this->unidadeProducao->create($data);
    }

    public function update(UnidadeProducao $unidade, array $data): UnidadeProducao
    {
        $unidade->update($data);
        return $unidade->fresh();
    }

    public function delete(UnidadeProducao $unidade): void
    {
        $unidade->delete();
    }

}
