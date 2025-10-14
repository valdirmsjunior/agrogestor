<?php

namespace App\Http\Resources\Rebanho;

use App\Http\Resources\BaseCollection;
use Illuminate\Http\Request;

class RebanhoCollection extends BaseCollection
{
    public $collects = RebanhoResource::class;
}
