<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStageItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'item_id' => $this->item_id,
            'title' => $this->item->title,
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'price' => $this->price,
            'count' => $this->count,
            'item_type' => $this->item->item_type,
            'deliver_type'=>"分期配送".($this->type==0?'/按周配送':'/按月配送'),
            'deliver_date'=> $this->type==0? getWeekDate($this->first_time,$this->timeset,$this->time):getMonthDate($this->first_time,$this->timeset,$this->time),
        ];
    }
}
