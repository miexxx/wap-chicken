<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'postal_code' => $this->postal_code,
            'tel' => $this->tel,
            'detail' => $this->detail,
            'user_name' => $this->user_name,
            'type' => $this->type
        ];
    }
}