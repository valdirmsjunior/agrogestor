<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produtor;

class ProdutoresSeeder extends Seeder
{
    public function run()
    {
        $produtores = [
            ['João Silva', '12345678901', '(11) 91111-1111', 'joao@fazenda.com', 'Rodovia SP-123, km 10, São Paulo'],
            ['Maria Oliveira', '23456789012', '(12) 92222-2222', 'maria@fazenda.com', 'Estrada Municipal 45, Campinas'],
            ['Carlos Souza', '34567890123', '(13) 93333-3333', 'carlos@fazenda.com', 'Fazenda Santa Rita, Ribeirão Preto'],
            ['Ana Costa', '45678901234', '(14) 94444-4444', 'ana@fazenda.com', 'Sítio Boa Vista, Sorocaba'],
            ['Pedro Almeida', '56789012345', '(15) 95555-5555', 'pedro@fazenda.com', 'Chácara São João, Santos'],
        ];

        foreach ($produtores as $index => $dados) {
            Produtor::create([
                'nome' => $dados[0],
                'cpf_cnpj' => $dados[1],
                'telefone' => $dados[2],
                'email' => $dados[3],
                'endereco' => $dados[4],
                'data_cadastro' => now()->subDays(5 - $index)->toDateString(),
            ]);
        }
    }
}
