<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $text="分期配送";
        switch ($this->type){
            case 0:
                $text="配送";
                break;
            case 1:
                $text="认养";
                break;
            case 2:
                $text="存入仓库";
                break;
            case 3:
                $text="分期配送";
                break;
            case 4:
                $text="充值";
                break;
            case 5:
                $text="自取";
                break;

        }
        return [
            'item_id' => $this->item_id,
            'title' => $this->item->title,
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'price' => $this->price,
            'unit_price'=>$this->item->unit_price,
            'count' => $this->count,
            'item_type' => $this->item_type,
            'deliver_type'=>$text,
        ];
    }
}
