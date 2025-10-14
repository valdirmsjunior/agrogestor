<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Propriedade;
use App\Models\UnidadeProducao;

class UnidadesProducaoSeeder extends Seeder
{
    public function run()
    {
        $propriedades = Propriedade::all();
        $culturas = [
            'Laranja Pera',
            'Melancia Crimson Sweet',
            'Goiaba Paluma',
            'Laranja Pera',
            'Melancia Crimson Sweet'
        ];
        $areas = [120.50, 80.25, 60.00, 90.30, 70.40];
        $coords = [
            ['lat' => -23.5505, 'lng' => -46.6333],
            ['lat' => -22.9068, 'lng' => -43.1729],
            ['lat' => -19.9167, 'lng' => -43.9345],
            ['lat' => -23.2075, 'lng' => -45.8922],
            ['lat' => -23.9608, 'lng' => -46.3339],
        ];

        foreach ($propriedades as $index => $propriedade) {
            UnidadeProducao::create([
                'nome_cultura' => $culturas[$index],
                'area_total_ha' => $areas[$index],
                'coordenadas_geograficas' => $coords[$index],
                'propriedade_id' => $propriedade->id,
            ]);
        }
    }
}
