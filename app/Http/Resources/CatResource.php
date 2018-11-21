<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->load('covers');
        return [
            'id' => $this->id,
            'item_id' => $this->item->id,
            'title' => $this->item->title,
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'status'=>$this->item->status,
            'price' => $this->item->price,
            'original_price' => $this->item->original_price,
            'sales_volume' => $this->item->sales_volume,
            'unit_price' => $this->item->unit_price,
            'type' =>$this->item->category->type,
            'stock'=>$this->item->stock,
            'count'=>$this->count
        ];
    }
}
