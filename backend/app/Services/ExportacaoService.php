<?php

namespace App\Services;

use App\Exports\PropriedadesExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportacaoService
{
    public function exportarPropriedades(): BinaryFileResponse
    {
        return Excel::download(new PropriedadesExport(), 'propriedades.xlsx');
    }
}
