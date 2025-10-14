<?php

namespace App\Http\Resources\Produtor;

use App\Http\Resources\BaseCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProdutorCollection extends BaseCollection
{
    public $collects = ProdutorResource::class;
}
