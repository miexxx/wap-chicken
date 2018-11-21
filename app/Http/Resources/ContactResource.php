<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'wechat_no' => $this->wechat_no,
            'code_img' => $this->code_img,
        ];
    }
}