<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propriedade extends Model
{
    use HasFactory;

    protected $table = 'propriedades';

    protected $fillable = [
        'nome', 'municipio', 'uf', 'inscricao_estadual', 'area_total', 'produtor_id'
    ];

    public function produtor(): BelongsTo
    {
        return $this->belongsTo(Produtor::class);
    }

    public function unidadesProducao(): HasMany
    {
        return $this->hasMany(UnidadeProducao::class, 'propriedade_id');
    }

    public function rebanhos(): HasMany
    {
        return $this->hasMany(Rebanho::class, 'propriedade_id');
    }
}
