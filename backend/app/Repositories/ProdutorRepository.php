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

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->produtor->with('propriedades')->paginate($perPage);
    }

    public function paginateWithFilters(int $perPage = 10, array $filters = [], ?array $sort = null): LengthAwarePaginator
    {
        $query = $this->produtor->newQuery();
        //dd($filters);

        // Aplica filtro por nome, se informado e não vazio
        if (!empty($filters['nome']) && trim($filters['nome']) !== '') {
            $query->where('nome', 'ilike', '%' . trim($filters['nome']) . '%');
        }

        // Aplica filtro por município via relacionamento, se informado
        if (!empty($filters['municipio']) && trim($filters['municipio']) !== '') {
            $municipio = trim($filters['municipio']);
            $query->whereHas('propriedades', function ($q) use ($municipio) {
                $q->where('municipio', 'ilike', '%' . $municipio . '%');
            });
        }

        // Aplica ordenação segura se parâmetros válidos foram passados
        if (!empty($sort['field']) && !empty($sort['order'])) {
            // Mapear order para 'asc' ou 'desc'
            $order = strtolower($sort['order']);
            if (!in_array($order, ['asc', 'desc'])) {
                // Caso order não seja válido, defina padrão
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
}
