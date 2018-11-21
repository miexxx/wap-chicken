<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'coupon_id' => $this->id,
            'money' => $this->money,
            'time' =>$this->time,
            'base_money' =>$this->base_money,
            'created_at'=>optional($this->created_at)->toDateTimeString(),
            'end_time'=>$this->pivot['end_time'],
            'status'=>$this->pivot['status'],
            'type'=>$this->type
        ];
    }
}