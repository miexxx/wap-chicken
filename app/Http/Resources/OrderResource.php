<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->items->load('covers');
        $this->stageItems->load('covers');
        return [
            'id' => $this->id,
            'sn' => $this->sn,
            'status' => $this->status_text,
            'count' => $this->items->sum('count')+$this->stageitems->sum('count'),
            'coupon'=>isset($this->coupon)?$this->coupon->money:0,
            'price'=> ($this->price-(isset($this->coupon)?$this->coupon->money:0)),
            'items' => OrderItemResource::collection($this->items),
            'stageItems'=>OrderStageItemResource::collection($this->stageItems),
            'address'=>new OrderAddressResource($this->address),
            'freight'=>$this->freight
        ];
    }
}
