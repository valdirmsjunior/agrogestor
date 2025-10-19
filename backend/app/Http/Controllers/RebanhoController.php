<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rebanho\StoreRebanhoRequest;
use App\Http\Requests\Rebanho\UpdateRebanhoRequest;
use App\Http\Resources\Rebanho\RebanhoCollection;
use App\Http\Resources\Rebanho\RebanhoResource;
use App\Services\RebanhoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RebanhoController extends Controller
{
    public function __construct(protected RebanhoService $rebanhoService)
    {
        $this->rebanhoService = $rebanhoService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'especie' => $request->get('especie'),
                'municipio' => $request->get('municipio'),
            ];

            $filters = array_filter($filters, fn ($value) => trim($value) !== '');
            $allowedSorts = ['id', 'especie', 'quantidade', 'finalidade', 'data_atualizacao', 'propriedade.nome', 'propriedade.municipio'];
            $sort = null;

            if ($request->filled('sort') && in_array($request->sort, $allowedSorts)) {
                $sort = [
                    'field' => $request->sort,
                    'order' => $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc'
                ];
            }

            $perPage = (int) $request->get('per_page', 10);

            $rebanhos = $this->rebanhoService->getAll($perPage, $filters, $sort);
            return new RebanhoCollection($rebanhos);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar rebanhos.'], 500);
        }
    }

    public function store(StoreRebanhoRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data_atualizacao'] = $data['data_atualizacao'] ?? now()->toDateString();
            $rebanho = $this->rebanhoService->create($data);

            return response()->json([
                'message' => 'Rebanho criado com sucesso',
                'data' => new RebanhoResource($rebanho)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $rebanho = $this->rebanhoService->getById($id);
            if (!$rebanho) {
                return response()->json(['message' => 'Rebanho não encontrado'], 404);
            }

            return response()->json([
                'message' => 'Rebanho encontrado com sucesso',
                'data' => new RebanhoResource($rebanho)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar rebanho.'], 500);
        }
    }

    public function update(UpdateRebanhoRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data_atualizacao'] = $data['data_atualizacao'] ?? now()->toDateString();
            $rebanho = $this->rebanhoService->update($id, $data);
            if (!$rebanho) {
                return response()->json(['message' => 'Rebanho não encontrado'], 404);
            }

            return response()->json([
                'message' => 'Rebanho atualizado com sucesso',
                'data' => new RebanhoResource($rebanho)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->rebanhoService->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Rebanho não encontrado'], 404);
            }

            return response()->json(['message' => 'Rebanho deletado com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
