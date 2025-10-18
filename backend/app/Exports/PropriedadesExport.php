<?php

namespace App\Exports;

use App\Models\Propriedade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PropriedadesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return Propriedade::with('produtor')->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Município',
            'UF',
            'Inscrição Estadual',
            'Área Total (ha)',
            'Produtor (Nome)',
            'Produtor (CPF/CNPJ)',
            'Data de Cadastro'
        ];
    }

    public function map($propriedade): array
    {
        return [
            $propriedade->nome,
            $propriedade->municipio,
            $propriedade->uf,
            $propriedade->inscricao_estadual ?? '—',
            $propriedade->area_total,
            $propriedade->produtor->nome ?? '—',
            $propriedade->produtor->cpf_cnpj ?? '—',
            $propriedade->created_at->format('d/m/Y')
        ];
    }
}
