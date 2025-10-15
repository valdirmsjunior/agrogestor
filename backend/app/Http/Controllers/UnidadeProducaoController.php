<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadeProducao\StoreUnidadeProducaoRequest;
use App\Http\Requests\UnidadeProducao\UpdateUnidadeProducaoRequest;
use App\Http\Resources\UnidadeProducao\UnidadeProducaoCollection;
use App\Http\Resources\UnidadeProducao\UnidadeProducaoResource;
use App\Services\UnidadeProducaoService;
use Exception;
use Illuminate\Http\JsonResponse;

class UnidadeProducaoController extends Controller
{
    public function __construct(protected UnidadeProducaoService $unidadeProducao)
    {
        $this->unidadeProducao = $unidadeProducao;
    }

    public function index()
    {
        try {
            $unidades = $this->unidadeProducao->getAll();
            return new UnidadeProducaoCollection($unidades);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar unidades de produção.'], 500);
        }
    }

    public function store(StoreUnidadeProducaoRequest $request): JsonResponse
    {
        try {
            $unidade = $this->unidadeProducao->create($request->validated());

            return response()->json([
                'message' => 'Unidade de produção criada com sucesso',
                'data' => new UnidadeProducaoResource($unidade)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $unidade = $this->unidadeProducao->getById($id);
            if (!$unidade) {
                return response()->json(['message' => 'Unidade de produção não encontrada'], 404);
            }
            return response()->json([
                'message' => 'Unidade de produção listada com sucesso',
                'data' => new UnidadeProducaoResource($unidade)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar unidade de produção.'], 500);
        }
    }

    public function update(UpdateUnidadeProducaoRequest $request, int $id): JsonResponse
    {
        try {
            $unidade = $this->unidadeProducao->update($id, $request->validated());
            if (!$unidade) {
                return response()->json(['message' => 'Unidade de produção não encontrada'], 404);
            }
            return response()->json([
                'message' => 'Unidade de produção atualizada com sucesso',
                'data' => new UnidadeProducaoResource($unidade)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->unidadeProducao->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Unidade de produção não encontrada'], 404);
            }

            return response()->json(['message' => 'Unidade de produção deletada com sucesso.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
