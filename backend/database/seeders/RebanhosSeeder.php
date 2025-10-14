<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Propriedade;
use App\Models\Rebanho;

class RebanhosSeeder extends Seeder
{
    public function run()
    {
        $propriedades = Propriedade::all();
        $especies = ['Suínos', 'Caprinos', 'Bovinos', 'Suínos', 'Bovinos'];
        $quantidades = [200, 80, 150, 120, 90];
        $finalidades = ['Corte', 'Leite', 'Reprodução', 'Corte', 'Leite'];

        foreach ($propriedades as $index => $propriedade) {
            Rebanho::create([
                'especie' => $especies[$index],
                'quantidade' => $quantidades[$index],
                'finalidade' => $finalidades[$index],
                'data_atualizacao' => now()->subDays(3 - ($index % 3))->toDateString(),
                'propriedade_id' => $propriedade->id,
            ]);
        }
    }
}
