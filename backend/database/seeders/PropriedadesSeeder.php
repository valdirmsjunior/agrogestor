<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produtor;
use App\Models\Propriedade;

class PropriedadesSeeder extends Seeder
{
    public function run()
    {
        $produtores = Produtor::all();
        $propriedades = [
            ['Fazenda Boa Vista', 'São Paulo', 'SP', '123456789', 500.75],
            ['Sítio Primavera', 'Campinas', 'SP', '234567890', 320.50],
            ['Chácara Verde', 'Ribeirão Preto', 'SP', '345678901', 410.25],
            ['Fazenda Sol Nascente', 'Sorocaba', 'SP', '456789012', 280.00],
            ['Sítio Recanto', 'Santos', 'SP', '567890123', 190.80],
        ];

        foreach ($propriedades as $index => $dados) {
            Propriedade::create([
                'nome' => $dados[0],
                'municipio' => $dados[1],
                'uf' => $dados[2],
                'inscricao_estadual' => $dados[3],
                'area_total' => $dados[4],
                'produtor_id' => $produtores[$index]->id,
            ]);
        }
    }
}
