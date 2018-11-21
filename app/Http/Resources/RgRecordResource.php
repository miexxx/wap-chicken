<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RgRecordResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'price' => $this->price,
            'total_price'=>  $this->items_price,
            'pay_time'=> $this->paid_at
        ];
    }
}