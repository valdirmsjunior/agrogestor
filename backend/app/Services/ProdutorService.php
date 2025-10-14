<?php

namespace App\Services;

use App\Models\Produtor;
use App\Repository\ProdutorRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ProdutorService
{
    public function __construct(protected ProdutorRepository $produtorRepository)
    {
        $this->produtorRepository = $produtorRepository;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        try {
            return $this->produtorRepository->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Erro ao listar produtores: ' . $e->getMessage());
        }
    }

    public function getById(int $id): ?Produtor
    {
        try {
            return $this->produtorRepository->find($id);
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar produtor: ' . $e->getMessage());
        }
    }

    public function create(array $data): Produtor
    {
        try {
            return $this->produtorRepository->create($data);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'cpf_cnpj')) {
                throw new Exception('CPF/CNPJ já cadastrado.');
            }
            if (str_contains($e->getMessage(), 'email')) {
                throw new Exception('E-mail já cadastrado.');
            }
            throw new Exception('Erro ao criar produtor: dados inválidos.');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(int $id, array $data): ?Produtor
    {
        try {
            $produtor = $this->produtorRepository->find($id);
            if (!$produtor) {
                return null;
            }
            return $this->produtorRepository->update($produtor, $data);
        } catch (QueryException $e) {
            throw new Exception('Erro ao atualizar produtor: violação de integridade.');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $produtor = $this->produtorRepository->find($id);
            if (!$produtor) {
                return false;
            }
            $this->produtorRepository->delete($produtor);
            return true;
        } catch (QueryException $e) {
            throw new Exception('Não é possível excluir: existem propriedades vinculadas.');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
