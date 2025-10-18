<?php

namespace App\Http\Controllers;

use App\Services\ExportacaoService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportacaoController extends Controller
{
    public function __construct(protected ExportacaoService $exportacaoService)
    {
        $this->exportacaoService = $exportacaoService;
    }

    public function propriedades(): BinaryFileResponse
    {
        return $this->exportacaoService->exportarPropriedades();
    }

    public function rebanhos()
    {
        return $this->exportacaoService->exportarRebanhosPorProdutor();
    }
}
