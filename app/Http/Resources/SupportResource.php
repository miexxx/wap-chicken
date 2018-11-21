<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource
{

    public function toArray($request)
    {
        $first = strtotime($this->created_at);
        $now = strtotime('now');
        $day = (int)round(($now-$first)/3600/24);
        $can_num = (int)($day / 2 - (int)getenv('PUT_EGGS') + $this->egg_num);
        $status = $this->status;
        if($can_num == 0 && $status == 1)
            $status = 2;
        elseif($can_num > 0 && $status == 1)
            $can_num = $this->can_num;
        elseif($status == 2)
            $can_num = 0;
        $this->update(['can_num'=>$can_num,'status'=>$status]);
        return [
            'id'=>$this->id,
            'item'=>new SupportItemResource($this->item),
            'egg_num'=>$this->egg_num,
            'can_num'=>$this->can_num,
            'can_kill'=>$this->can_num >=0 ?1:0,
            'can_dm'=>$day>720?1:0,
            'can_day_kill'=>$this->can_num >=0 ? 0:$this->can_num/(-2),
            'status'=>$this->status
        ];
    }
}