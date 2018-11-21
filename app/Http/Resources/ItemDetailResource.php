<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'covers' => CoverResource::collection($this->whenLoaded('covers')),
            'price' => $this->price,
            'original_price' => $this->original_price,
            'unit_price'=>$this->unit_price,
            'freight' => $this->freight,
            'sales_volume' => $this->sales_volume,
            'stock' => $this->stock,
            'details' => $this->details,
            'parameter' => $this->parameter,
            'packaging' =>$this->packaging,
            'type' =>$this->category->type,
            'item_type' => $this->item_type,
            'is_extension'=>$this->is_extension,
            'count'=>1,
            'is_favorite'=>$this->favorite()
        ];
    }
}
