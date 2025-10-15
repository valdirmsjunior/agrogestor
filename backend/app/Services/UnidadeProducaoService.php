<?php

namespace App\Services;

use App\Models\UnidadeProducao;
use App\Repositories\UnidadeProducaoRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UnidadeProducaoService
{
    public function __construct(protected UnidadeProducaoRepository $unidadeProducaoRepository)
    {
        $this->unidadeProducaoRepository = $unidadeProducaoRepository;
    }

    public function getAll(int $perPage = 10)
    {
        return $this->unidadeProducaoRepository->paginate($perPage);
    }

    public function getById(int $id): ?UnidadeProducao
    {
        return $this->unidadeProducaoRepository->find($id);
    }

    public function create(array $data): UnidadeProducao
    {
        try {
            return $this->unidadeProducaoRepository->create($data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'propriedade_id')) {
                throw new Exception('Propriedade informada não existe.');
            }
            throw new Exception('Erro ao criar unidade de produção: dados inválidos.');
        }
    }

    public function update(int $id, array $data): ?UnidadeProducao
    {
        $unidade = $this->unidadeProducaoRepository->find($id);
        if (!$unidade) {
            return null;
        }

        try {
            return $this->unidadeProducaoRepository->update($unidade, $data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'propriedade_id')) {
                throw new Exception('Propriedade informada não existe.');
            }
            throw new Exception('Erro ao atualizar unidade de produção: dados inválidos.');
        }
    }

    public function delete(int $id): bool
    {
        $unidade = $this->unidadeProducaoRepository->find($id);
        if (!$unidade) {
            return false;
        }

        try {
            $this->unidadeProducaoRepository->delete($unidade);
            return true;
        } catch (QueryException $e) {
            throw new Exception('Erro ao excluir unidade de produção.');
        }
    }
}
