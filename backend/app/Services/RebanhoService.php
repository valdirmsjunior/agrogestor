<?php

namespace App\Services;

use App\Models\Rebanho;
use App\Repositories\RebanhoRepository;
use Exception;
use Illuminate\Database\QueryException;

class RebanhoService
{
    public function __construct(protected RebanhoRepository $rebanhoRepository)
    {
        $this->rebanhoRepository = $rebanhoRepository;
    }

    public function getAll(int $perPage = 10, array $filters = [], ?array $sort = [])
    {
        return $this->rebanhoRepository->paginateWithFilters($perPage, $filters, $sort);
    }

    public function getById(int $id): ?Rebanho
    {
        return $this->rebanhoRepository->find($id);
    }

    public function create(array $data): Rebanho
    {
        try {
            return $this->rebanhoRepository->create($data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'propriedade_id')) {
                throw new Exception('Propriedade informada não existe.');
            }
            if (str_contains($e->getMessage(), 'especie')) {
                throw new Exception('Espécie inválida. Escolha entre: ' . implode(', ', Rebanho::ESPECIES_PERMITIDAS));
            }
            throw new Exception('Erro ao criar rebanho: dados inválidos.');
        }
    }

    public function update(int $id, array $data): ?Rebanho
    {
        $rebanho = $this->rebanhoRepository->find($id);
        if (!$rebanho) {
            return null;
        }

        try {
            return $this->rebanhoRepository->update($rebanho, $data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'propriedade_id')) {
                throw new Exception('Propriedade informada não existe.');
            }
            if (str_contains($e->getMessage(), 'especie')) {
                throw new Exception('Espécie inválida. Escolha entre: ' . implode(', ', Rebanho::ESPECIES_PERMITIDAS));
            }
            throw new Exception('Erro ao atualizar rebanho: dados inválidos.');
        }
    }

    public function delete(int $id): bool
    {
        $rebanho = $this->rebanhoRepository->find($id);
        if (!$rebanho) {
            return false;
        }

        try {
            $this->rebanhoRepository->delete($rebanho);
            return true;
        } catch (QueryException $e) {
            throw new Exception('Erro ao excluir rebanho.');
        }
    }
}
