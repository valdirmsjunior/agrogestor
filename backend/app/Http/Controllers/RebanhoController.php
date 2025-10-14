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

    public function index()
    {
        try {
            $rebanhos = $this->rebanhoService->getAll();
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
            return response()->json(new RebanhoResource($rebanho), 201);
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
            return response()->json(new RebanhoResource($rebanho));
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
            return response()->json(new RebanhoResource($rebanho));
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
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
