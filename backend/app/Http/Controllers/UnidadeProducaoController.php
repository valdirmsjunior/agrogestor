<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadeProducao\StoreUnidadeProducaoRequest;
use App\Http\Requests\UnidadeProducao\UpdateUnidadeProducaoRequest;
use App\Http\Resources\UnidadeProducao\UnidadeProducaoCollection;
use App\Http\Resources\UnidadeProducao\UnidadeProducaoResource;
use App\Services\UnidadeProducaoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnidadeProducaoController extends Controller
{
    public function __construct(protected UnidadeProducaoService $unidadeProducao)
    {
        $this->unidadeProducao = $unidadeProducao;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'nome_cultura' => $request->get('nome_cultura'),
                'propriedade' => $request->get('propriedade_id'),
                'municipio' => $request->get('municipio')
            ];

            $filters = array_filter($filters, fn ($value) => trim($value) !== '');

            $allowedSorts = ['id', 'nome_cultura', 'area_total_ha', 'coordenadas_geograficas', 'propriedade.nome', 'propriedade.municipio'];
            $sort = null;
            if ($request->filled('sort') && in_array($request->sort, $allowedSorts)) {
                $sort = [
                    'field' => $request->sort,
                    'order' => $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc'
                ];
            }

            $perPage = (int) $request->get('per_page', 10);
            $unidades = $this->unidadeProducao->getAll($perPage, $filters, $sort);

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
