<?php

namespace App\Repositories;

use App\Models\Propriedade;
use App\Models\Rebanho;
use App\Models\UnidadeProducao;

class RelatorioRepository
{
    public static function propriedadesPorMunicipio()
    {
        return Propriedade::select('municipio', 'uf')
            ->whereNotNull('municipio')
            ->whereNotNull('uf')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('municipio', 'uf')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();
    }

    public static function animaisPorEspecie()
    {
        return Rebanho::select('especie')
            ->whereNotNull('especie')
            ->selectRaw('COALESCE(SUM(quantidade),0) as total')
            ->groupBy('especie')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();
    }

    public static function hectaresPorCultura()
    {
        return UnidadeProducao::select('nome_cultura')
            ->whereNotNull('nome_cultura')
            ->selectRaw('COALESCE(SUM(area_total_ha),0) as total_ha')
            ->groupBy('nome_cultura')
            ->orderBy('total_ha', 'desc')
            ->get()
            ->toArray();
    }
}
