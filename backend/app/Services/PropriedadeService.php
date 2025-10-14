<?php

namespace App\Services;

use App\Models\Propriedade;
use App\Repositories\PropriedadeRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class PropriedadeService
{
    public function __construct(protected PropriedadeRepository $propriedadeRepository)
    {
        $this->propriedadeRepository = $propriedadeRepository;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        try {
            return $this->propriedadeRepository->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Erro ao listar propriedades: ' . $e->getMessage());
        }
    }

    public function getById(int $id): ?Propriedade
    {
        try {
            return $this->propriedadeRepository->find($id);
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar propriedade: ' . $e->getMessage());
        }
    }

    public function create(array $data): Propriedade
    {
        try {
            return $this->propriedadeRepository->create($data);
        } catch (Exception $e) {
            throw new Exception('Erro ao criar propriedade: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data): ?Propriedade
    {
        $propriedade = $this->propriedadeRepository->find($id);
        if (!$propriedade) {
            return null;
        }

        try {
            return $this->propriedadeRepository->update($propriedade, $data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'produtor_id')) {
                throw new Exception('Produtor informado não existe.');
            }
            throw new Exception('Erro ao atualizar propriedade: dados inválidos.');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $propriedade = $this->propriedadeRepository->find($id);
            if (!$propriedade) {
                return false;
            }
            $this->propriedadeRepository->delete($propriedade);
            return true;
        } catch (QueryException $e) {
            throw new Exception('Erro ao deletar propriedade: ' . $e->getMessage());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
