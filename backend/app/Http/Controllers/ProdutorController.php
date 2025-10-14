<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdutorRequest;
use App\Http\Requests\UpdateProdutorRequest;
use App\Models\Produtor;
use App\Services\ProdutorService;
use Illuminate\Http\JsonResponse;

class ProdutorController extends Controller
{
    public function __construct(protected ProdutorService $service)
    {
        $this->service = $service;
    }
    public function index(): JsonResponse
    {
        return response()->json($this->service->getAll());
    }

    public function store(StoreProdutorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['data_cadastro'] = $data['data_cadastro'] ?? now()->toDateString();

        $produtor = $this->service->create($data);
        return response()->json($produtor, 201);
    }

    public function show(string $id): JsonResponse
    {
        $produtor = $this->service->getById($id);
        if (!$produtor) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }
        return response()->json($produtor);
    }

    public function update(UpdateProdutorRequest $request, int $id): JsonResponse
    {
        $produtor = $this->service->update($id, $request->validated());
        if (!$produtor) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }
        return response()->json($produtor);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Produtor não encontrado'], 404);
        }
        return response()->json(null, 204);
    }
}
