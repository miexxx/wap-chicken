<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ItemResource;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

class StorehouseResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'item'=>new StorgeItemResource($this->item),
            'num'=>$this->num,
        ];
    }
}