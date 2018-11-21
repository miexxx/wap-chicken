<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletApplyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'money' => $this->money,
            'created_at'=>date('Y-m-d H:i:s',strtotime($this->created_at)),
            'status'=>$this->state,
            'updated_at'=>$this->state>0?date('Y-m-d H:i:s',strtotime($this->updated_at)):null,
        ];
    }
}