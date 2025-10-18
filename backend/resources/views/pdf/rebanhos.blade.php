<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rebanhos por Produtor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .produtor {
            margin-bottom: 40px;
        }

        .produtor h2 {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Relatório de Rebanhos por Produtor</h1>
    <p><strong>Data de Emissão:</strong> {{ now()->format('d/m/Y') }}</p>

    @foreach ($produtores as $produtor)
        <div class="produtor">
            <h2>{{ $produtor->nome }} (CPF/CNPJ: {{ $produtor->cpf_cnpj }})</h2>
            <table>
                <thead>
                    <tr>
                        <th>Espécie</th>
                        <th>Quantidade</th>
                        <th>Finalidade</th>
                        <th>Propriedade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produtor->propriedades as $propriedade)
                        @foreach ($propriedade->rebanhos as $rebanho)
                            <tr>
                                <td>{{ $rebanho->especie }}</td>
                                <td>{{ $rebanho->quantidade }}</td>
                                <td>{{ $rebanho->finalidade }}</td>
                                <td>{{ $propriedade->nome }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Total de animais: {{ $produtor->propriedades->flatMap->rebanhos->sum('quantidade') }}
            </div>
        </div>
    @endforeach
</body>

</html>
