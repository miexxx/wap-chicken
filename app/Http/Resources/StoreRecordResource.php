<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreRecordResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'item' =>new SupportItemResource($this->storeitem->item),
            'type' => $this->type,
            'count'=>$this->count,
            'created_at'=>date('Y-m-d H:i:s',strtotime($this->created_at)),
            'touser'=>$this->type == 1?$this->toUser->nickname:null,
        ];
    }
}