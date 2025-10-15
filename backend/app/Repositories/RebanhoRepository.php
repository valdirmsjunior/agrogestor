<?php

namespace App\Repositories;

use App\Models\Rebanho;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RebanhoRepository
{
    public function __construct(protected Rebanho $rebanho)
    {
        $this->rebanho = $rebanho;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->rebanho->with('propriedade')->paginate($perPage);
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
}
