<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Http\Resources\OrderResource;
use App\Models\About;
use App\Models\Order;
use App\Models\Recharge;
use App\Models\Wallet;
use App\Models\WalletApply;
use Illuminate\Http\Request;
use App\Http\Resources\WalletApplyResource;
class WalletController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function show(){
        return response()->json($this->user->wallet()->get(['money','state','can_money']));
    }

    public function cash(Request $request){
        $can_money = $this->user->wallet->can_money;
        if($request->get('money') &&  $can_money >= $request->get('money') &&$this->user->wallet->state ==Wallet::NORNAL ) {
            $this->user->wallet->state=Wallet::WAIT;
            $this->user->wallet->can_money-=$request->get('money');
            $this->user->wallet->save();
            $walletapply = new WalletApply();
            $walletapply->user_id = $this->user->id;
            $walletapply->money = $request->get('money');
            $walletapply->save();
            return response()->json(['status' => '1', 'msg' => '提现申请成功']);
        }

        return response()->json(['status'=>'0','msg'=>'提现申请失败,提现金额异常']);
    }

    public function recharge(Recharge $recharge){

            //todo
            $order = (new Order())->submitRecharge($this->user,$recharge);
            return response()->json(['status'=>'1','order'=>array(
                'id' =>$order->id,
                'price'=>$order->price,
            )]);

    }

    public function cashrecord(){
        $walletsapply = WalletApply::where('user_id',$this->user->id)->get();
        return api()->collection($walletsapply,WalletApplyResource::class);
    }
}
