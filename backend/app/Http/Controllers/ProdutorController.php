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

class ProdutorController extends Controller
{
    public function __construct(protected ProdutorService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        try {
            $produtores = $this->service->getAll();
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
            return response()->json(new ProdutorResource($produtor), 201);
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
            return response()->json(new ProdutorResource($produtor));
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
            return response()->json(new ProdutorResource($produtor));
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
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
