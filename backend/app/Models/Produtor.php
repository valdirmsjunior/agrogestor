<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produtor extends Model
{
    use HasFactory;

    protected $table = 'produtores';

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'telefone',
        'email',
        'endereco',
        'data_cadastro'
    ];

    protected $casts = [
        'data_cadastro' => 'date',
    ];

    public function propriedades(): HasMany
    {
        return $this->hasMany(Propriedade::class);
    }
}
