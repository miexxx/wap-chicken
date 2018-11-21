<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserLevel;
use App\Http\Resources\UserLevelResource;

class SharerCenterResource extends JsonResource
{

    public function toArray($request)
    {

        $userLevel = UserLevel::where('level','>',$this->is_shared)->orderBy('level','asc')->first();

        $shared_at = $this->shared_at;
        $stt_at = $this->stt_at;

        $userPromotes = $this->userPromote()->whereHas('order', function ($query) use($shared_at,$stt_at) {
            $query->whereIn('status', [2,4])->whereBetween('delivered_at', [$shared_at,$stt_at]);
        })->get();

        $userNoPromotes = $this->userPromote()->whereHas('order', function ($query) {
            $query->where('status', 1);
        })->get();

        $userInvites = $this->userInvite()->whereHas('order', function ($query) use($shared_at,$stt_at) {
            $query->whereIn('status', [2,4])->whereBetween('delivered_at', [$shared_at,$stt_at]);
        })->get();

        $userNoInvites = $this->userInvite()->whereHas('order', function ($query) {
            $query->where('status', 1);
        })->get();

        $orders = $this->orders()->whereIn('status', [2,4])->whereBetween('delivered_at', [$shared_at,$stt_at])->get();

        $wait_orders = $this->orders()->where('status', 1)->get();

        return [
            'avatarUrl' => $this->avatarUrl,
            'nickname' => $this->nickname,
            //'levelName' => $this->userLevel->name,
            'current_level' => new UserLevelResource($this->userLevel),
            'next_level' => $userLevel?new UserLevelResource($userLevel):null,
            'earnings' => $userPromotes->sum('pro_money') + $userInvites->sum('pro_money'),
            'wait_earnings' => $userNoPromotes->sum('pro_money') +  $userNoInvites->sum('pro_money'),
            'pro_money' => $userPromotes->sum('money') + $userInvites->sum('money'),
            'wait_pro_money' => $userNoPromotes->sum('money') + $userNoInvites->sum('money'),
            'order_money' => $orders->sum('price'),
            'wait_order_money' => $wait_orders->sum('price'),
            'can_money' => $this->wallet->can_money,
            'child_count' => $this->childrens->count(),
            'pro_order_count' =>  $userPromotes->count(),
        ];
    }

}