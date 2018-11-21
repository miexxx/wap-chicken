<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StorgeItemResource extends JsonResource
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
            'title' => $this->title,
            'cover' => new CoverResource($this->whenLoaded('covers', function () {
                return $this->covers->first();
            })),
            'status'=>$this->status,
            'price' => $this->price,
            'stock' => $this->stock,
            'original_price' => $this->original_price,
            'sales_volume' => $this->sales_volume,
            'unit_price' => $this->unit_price,
            'type' =>$this->category->type,
            'item_type' => $this->item_type,
            'is_extension'=>$this->is_extension,

        ];
    }
}
