<?php

namespace App\Http\Controllers;

use App\Services\ExportacaoService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportacaoContoller extends Controller
{
    public function __construct(protected ExportacaoService $exportacaoService)
    {
        $this->exportacaoService = $exportacaoService;
    }

    public function propriedades(): BinaryFileResponse
    {
        return $this->exportacaoService->exportarPropriedades();
    }
}
