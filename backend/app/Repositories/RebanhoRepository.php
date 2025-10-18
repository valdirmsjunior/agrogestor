<?php

namespace App\Repositories;

use App\Models\Rebanho;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RebanhoRepository
{
    public function __construct(protected Rebanho $rebanho)
    {
        $this->rebanho = $rebanho;
    }

    public function paginateWithFilters(int $perPage = 10, array $filters = [], ?array $sort = null): LengthAwarePaginator
    {
        $query = $this->rebanho->newQuery();

        if (!empty($filters['especie']) && trim($filters['especie']) !== '') {
            $query->where('especie', 'ilike', '%' . trim($filters['especie']) . '%');
        }
        if (!empty($filters['municipio']) && trim($filters['municipio']) !== '') {
            $query->where('municipio', 'ilike', '%' . trim($filters['municipio']) . '%');
        }

        if (!empty($sort['field']) && !empty($sort['order'])) {
            $order = strtolower($sort['order']);
            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }
            $query->orderBy($sort['field'], $order);
        }

        return $query->with(['propriedade'])->paginate($perPage);
    }

    public function find(int $id): ?Rebanho
    {
        return $this->rebanho->with('propriedade')->find($id);
    }

    public function create(array $data): Rebanho
    {
        return $this->rebanho->create($data);
    }

    public function update(Rebanho $rebanho, array $data): Rebanho
    {
        $rebanho->update($data);
        return $rebanho->fresh();
    }

    public function delete(Rebanho $rebanho): void
    {
        $rebanho->delete();
    }

    public function getRebanhosPorProdutor(int $produtorId): Collection
    {
        return $this->rebanho
            ->whereHas('propriedade', fn ($q) => $q->where('produtor_id', $produtorId))
            ->get();
    }
}
