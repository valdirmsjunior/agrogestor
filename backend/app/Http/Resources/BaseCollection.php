<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total() ?? $this->collection->count(),
                'per_page' => $this->perPage() ?? 10,
                'current_page' => $this->currentPage() ?? 1,
                'last_page' => $this->lastPage() ?? 1,
            ],
        ];
    }
}
