<?php

namespace App\Repositories;

use App\Models\Produtor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProdutorRepository
{
    public function __construct(protected Produtor $produtor)
    {
        $this->produtor = $produtor;
    }

    public function all(): Collection
    {
        return $this->produtor->all();
    }

    public function paginateWithFilters(int $perPage = 10, array $filters = [], ?array $sort = null): LengthAwarePaginator
    {
        $query = $this->produtor->newQuery();

        if (!empty($filters['nome']) && trim($filters['nome']) !== '') {
            $query->where('nome', 'ilike', '%' . trim($filters['nome']) . '%');
        }
        if (!empty($filters['municipio']) && trim($filters['municipio']) !== '') {
            $municipio = trim($filters['municipio']);
            $query->whereHas('propriedades', function ($q) use ($municipio) {
                $q->where('municipio', 'ilike', '%' . $municipio . '%');
            });
        }

        if (!empty($sort['field']) && !empty($sort['order'])) {
            $order = strtolower($sort['order']);
            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }
            $query->orderBy($sort['field'], $order);
        }

        return $query->with('propriedades')->paginate($perPage);
    }

    public function find(int $id): ?Produtor
    {
        return $this->produtor->with('propriedades')->find($id);
    }

    public function create(array $data): Produtor
    {
        return $this->produtor->create($data);
    }

    public function update(Produtor $produtor, array $data): Produtor
    {
        $produtor->update($data);
        return $produtor->fresh();
    }

    public function delete(Produtor $produtor): void
    {
        $produtor->delete();
    }

    public function getProdutoresPorRebanho(): Collection
    {
        $produtores = Produtor::whereHas('propriedades.rebanhos')->with('propriedades.rebanhos')->get();

        return $produtores;
    }
}
