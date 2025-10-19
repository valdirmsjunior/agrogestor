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

    public function paginateWithFilters(int $perPage = 10, array $filters = [], ?array $sort = null): LengthAwarePaginator
    {
        \Log::info($filters);
        $query = $this->unidadeProducao->newQuery();

        if (!empty($filters['nome_cultura']) && trim($filters['nome_cultura']) !== '') {
            $query->where('nome_cultura', 'ilike', '%' . trim($filters['nome_cultura']) . '%');
        }
        if (!empty($filters['propriedade']) && trim($filters['propriedade']) !== '') {
            $query->where('propriedade', 'ilike', '%' . trim($filters['propriedade']) . '%');
        }
        if (!empty($filters['municipio']) && trim($filters['municipio']) !== '') {
            $municipio = trim($filters['municipio']);
            $query->whereHas('propriedade', function ($q) use ($municipio) {
                $q->where('municipio', 'ilike', '%' . $municipio . '%');
            });
        }

        if (!empty($sort['field']) && !empty($sort['order'])) {
            $order = strtolower($sort['order']);
            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }

            if ($sort['field'] === 'propriedade.nome') {
                $query->join('propriedades', 'unidades_producao.propriedade_id', '=', 'propriedades.id')
                    ->orderBy('propriedades.nome', $order);
            } elseif ($sort['field'] === 'propriedade.municipio') {
                $query->join('propriedades', 'unidades_producao.propriedade_id', '=', 'propriedades.id')
                    ->orderBy('propriedades.municipio', $order);
            } else {
                $query->orderBy($sort['field'], $order);
            }
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->with('propriedade')->paginate($perPage);
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
