<?php

namespace App\Http\Controllers;

use App\Http\Requests\Propriedade\StorePropriedadeRequest;
use App\Http\Requests\Propriedade\UpdatePropriedadeRequest;
use App\Http\Resources\Propriedade\PropriedadeCollection;
use App\Http\Resources\Propriedade\PropriedadeResource;
use App\Services\PropriedadeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropriedadeController extends Controller
{
    public function __construct(protected PropriedadeService $propriedadeService)
    {
        $this->propriedadeService = $propriedadeService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'nome' => $request->get('nome'),
                'municipio' => $request->get('municipio'),
            ];

            $filters = array_filter($filters, fn ($value) => trim($value) !== '');

            $allowedSorts = ['id', 'nome', 'municipio', 'uf', 'inscricao_estadual', 'produtor_id'];
            $sort = null;

            if ($request->filled('sort') && in_array($request->sort, $allowedSorts)) {
                $sort = [
                    'field' => $request->sort,
                    'order' => $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc'
                ];
            }

            $perPage = (int) $request->get('per_page', 10);

            $propriedades = $this->propriedadeService->getAll($perPage, $filters, $sort);
            return new PropriedadeCollection($propriedades);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(StorePropriedadeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $propriedade = $this->propriedadeService->create($data);
            return response()->json([
                'message' => 'Propriedade adicionada com sucesso',
                'data' => new PropriedadeResource($propriedade)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(int $id)
    {
        try {
            $propriedade = $this->propriedadeService->getById($id);
            if (!$propriedade) {
                return response()->json(['message' => 'Propriedade não encontrada'], 404);
            }
            return response()->json(new PropriedadeResource($propriedade));
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar propriedade'], 500);
        }
    }

    public function update(UpdatePropriedadeRequest $request, int $id): JsonResponse
    {
        try {
            $propriedade = $this->propriedadeService->update($id, $request->validated());
            if (!$propriedade) {
                return response()->json(['message' => 'Propriedade não encontrada'], 404);
            }
            return response()->json([
                'message' => 'Propriedade atualizada com sucesso',
                'data' => new PropriedadeResource($propriedade)
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id)
    {
        try {
            $deleted = $this->propriedadeService->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Propriedade não encontrada'], 404);
            }
            return response()->json(['message' => 'Propriedade deletada com sucesso.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao deletar propriedade'], 500);
        }
    }
}
