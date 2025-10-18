<?php

namespace App\Http\Controllers;

use App\Services\RelatorioService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RelatorioController extends Controller
{
    public function __construct(protected RelatorioService $relatorioService)
    {
        $this->relatorioService = $relatorioService;
    }

    public function index(): JsonResponse
    {
        try {
            $relatorio = $this->relatorioService->getRelatorios();
            return response()->json([
                'success' => true,
                'data' => $relatorio
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao gerar relatório: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
