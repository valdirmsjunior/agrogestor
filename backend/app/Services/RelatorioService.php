<?php

namespace App\Services;

use App\Repositories\RelatorioRepository;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RelatorioService
{
    public function __construct(protected RelatorioRepository $relatorioRepository)
    {
        $this->relatorioRepository = $relatorioRepository;
    }

    public function getRelatorios()
    {
        try {
            return Cache::remember('relatorio_resumo', 60, function () {

                $propriedades = $this->relatorioRepository->propriedadesPorMunicipio();
                $animais = $this->relatorioRepository->animaisPorEspecie();
                $hectares = $this->relatorioRepository->hectaresPorCultura();

                if (empty($propriedades) && empty($animais) && empty($hectares)) {
                    Log::warning('Relatório retornou dados vazios - possível problema no banco');
                }

                return [
                    'propriedades_por_municipio' => $propriedades,
                    'animais_por_especie' => $animais,
                    'hectares_por_cultura' => $hectares,
                ];
            });
        } catch (Exception $e) {
            Cache::forget('relatorio_resumo');
            Log::error('Falha no serviço de relatório: ' . $e->getMessage());

            throw $e;
        }
    }
}
