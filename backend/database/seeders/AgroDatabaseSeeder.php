<?php

namespace Database\Seeders;

use App\Models\Produtor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgroDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar produtor
        $produtor = Produtor::create([
            'nome' => 'João da Fazenda',
            'cpf_cnpj' => '12345678901',
            'telefone' => '(11) 99999-9999',
            'email' => 'joao@fazenda.com',
            'endereco' => 'Rodovia SP-123, km 45, São Paulo',
            'data_cadastro' => now()->toDateString(),
        ]);

        // Criar propriedade
        $propriedade = $produtor->propriedades()->create([
            'nome' => 'Fazenda Boa Vista',
            'municipio' => 'São Paulo',
            'uf' => 'SP',
            'inscricao_estadual' => '123456789',
            'area_total' => 500.75,
        ]);

        // Unidades de produção (culturas)
        $culturas = [
            ['Laranja Pera', 120.50],
            ['Melancia Crimson Sweet', 80.25],
            ['Goiaba Paluma', 60.00],
        ];

        foreach ($culturas as [$cultura, $area]) {
            $propriedade->unidadesProducao()->create([
                'nome_cultura' => $cultura,
                'area_total_ha' => $area,
                'coordenadas_geograficas' => ['lat' => -23.5505, 'lng' => -46.6333],
            ]);
        }

        // Rebanhos (espécies)
        $rebanhos = [
            ['Suínos', 200, 'Corte'],
            ['Caprinos', 80, 'Leite'],
            ['Bovinos', 150, 'Reprodução'],
        ];

        foreach ($rebanhos as [$especie, $quantidade, $finalidade]) {
            $propriedade->rebanhos()->create([
                'especie' => $especie,
                'quantidade' => $quantidade,
                'finalidade' => $finalidade,
                'data_atualizacao' => now()->toDateString(),
            ]);
        }
    }
}
