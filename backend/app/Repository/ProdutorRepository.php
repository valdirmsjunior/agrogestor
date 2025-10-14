<?php

namespace App\Repository;

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
