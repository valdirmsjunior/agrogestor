<?php

namespace App\Services;

use App\Models\Produtor;
use App\Repository\ProdutorRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProdutorService
{
    public function __construct(protected ProdutorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function getById(int $id): ?Produtor
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Produtor
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): ?Produtor
    {
        $produtor = $this->repository->find($id);
        if (!$produtor) {
            return null;
        }

        return $this->repository->update($produtor, $data);
    }

    public function delete(int $id): bool
    {
        $produtor = $this->repository->find($id);
        if (!$produtor) {
            return false;
        }

        $this->repository->delete($produtor);
        return true;
    }
}
