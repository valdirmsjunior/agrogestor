<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produtor\StoreProdutorRequest;
use App\Http\Requests\Produtor\UpdateProdutorRequest;
use App\Http\Resources\Produtor\ProdutorCollection;
use App\Http\Resources\Produtor\ProdutorResource;
use App\Models\Produtor;
use App\Services\ProdutorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutorController extends Controller
{
    public function __construct(protected ProdutorService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        try {
            $filters = [
                'nome' => $request->get('nome'),
                'cpf_cnpj' => $request->get('cpf_cnpj'),
            ];

            $filters = array_filter($filters, fn ($value) => trim($value) !== '');

            $allowedSorts = ['id', 'nome', 'cpf_cnpj', 'email', 'telefone', 'created_at'];
            $sort = null;
            if ($request->filled('sort') && in_array($request->sort, $allowedSorts)) {
                $sort = [
                    'field' => $request->sort,
                    'order' => $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc'
                ];
            }

            $perPage = (int) $request->get('per_page', 10);

            $produtores = $this->service->getAll($perPage, $filters, $sort);
            return new ProdutorCollection($produtores);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(StoreProdutorRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data_cadastro'] = $data['data_cadastro'] ?? now()->toDateString();
            $produtor = $this->service->create($data);

            return response()->json([
                'message' => 'Produtor adicionado com sucesso',
                'data' => new ProdutorResource($produtor)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $produtor = $this->service->getById($id);
            if (!$produtor) {
                return response()->json(['message' => 'Produtor não encontrado'], 404);
            }

            return response()->json([
                'message' => 'Produtor encontrado com sucesso',
                'data' => new ProdutorResource($produtor)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar produtor'], 500);
        }
    }

    public function update(UpdateProdutorRequest $request, int $id): JsonResponse
    {
        try {
            $produtor = $this->service->update($id, $request->validated());
            if (!$produtor) {
                return response()->json(['message' => 'Produtor não encontrado'], 404);
            }

            return response()->json([
                'message' => 'Produtor atualizado com sucesso',
                'data' => new ProdutorResource($produtor)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->service->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Produtor não encontrado'], 404);
            }
            return response()->json(['message' => 'Produtor deletado com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
