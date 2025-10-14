<?php

namespace App\Repositories;

use App\Models\Propriedade;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropriedadeRepository
{
    public function __construct(protected Propriedade $propriedade)
    {
        $this->propriedade = $propriedade;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->propriedade->with(['produtor', 'unidadesProducao', 'rebanhos'])->paginate($perPage);
    }

    public function find(int $id): ?Propriedade
    {
        return $this->propriedade->with(['produtor', 'unidadesProducao', 'rebanhos'])->find($id);
    }

    public function create(array $data): Propriedade
    {
        return $this->propriedade->create($data);
    }

    public function update(Propriedade $propriedade, array $data): Propriedade
    {
        $propriedade->update($data);
        return $propriedade->fresh();
    }

    public function delete(Propriedade $propriedade): void
    {
        $propriedade->delete();
    }
}
