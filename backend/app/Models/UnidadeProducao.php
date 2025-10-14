<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnidadeProducao extends Model
{
    use HasFactory;

    protected $table = 'unidades_producao';

    protected $fillable = [
        'nome_cultura', 'area_total_ha', 'coordenadas_geograficas', 'propriedade_id'
    ];

    protected $casts = [
        'coordenadas_geograficas' => 'array',
    ];

    public function propriedade(): BelongsTo
    {
        return $this->belongsTo(Propriedade::class);
    }
}
