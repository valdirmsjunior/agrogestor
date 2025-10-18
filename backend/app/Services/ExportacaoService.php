<?php

namespace App\Services;

use App\Exports\PropriedadesExport;
use App\Repositories\ProdutorRepository;
use App\Repositories\RebanhoRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportacaoService
{
    public function __construct(
        protected ProdutorRepository $produtorRepository,
        protected RebanhoRepository $rebanhoRepository
    ) {
        $this->produtorRepository = $produtorRepository;
        $this->rebanhoRepository = $rebanhoRepository;
    }
    public function exportarPropriedades(): BinaryFileResponse
    {
        return Excel::download(new PropriedadesExport(), 'propriedades.xlsx');
    }

    public function exportarRebanhosPorProdutor()
    {
        $produtores = $this->produtorRepository->getProdutoresPorRebanho();

        $pdf = Pdf::loadView('pdf.rebanhos', ['produtores' => $produtores]);

        return $pdf->download("rebanhos.pdf");
    }
}
