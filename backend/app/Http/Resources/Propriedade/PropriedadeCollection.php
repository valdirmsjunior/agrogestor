<?php

namespace App\Http\Resources\Propriedade;

use App\Http\Resources\BaseCollection;
use Illuminate\Http\Request;

class PropriedadeCollection extends BaseCollection
{
    public $collects = PropriedadeResource::class;
}
