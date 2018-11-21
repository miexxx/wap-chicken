<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Http\Resources\CouponResource;
use App\Models\About;
use App\Models\Coupon;
use App\Models\Wallet;
use App\Models\WalletApply;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(){
        $coupons = (new Coupon())->orderBy('created_at','desc')->where('type',0)->get(['id','money','time','base_money','type']);
        return response()->json($coupons);
    }

    public function show(){
        return api()->collection($this->user->coupons()->get(),CouponResource::class);
    }

    public function accpet(Coupon $coupon){
        $end_time = date("Y-m-d H:i:s",strtotime("+".$coupon->time."day"));
        //dd($end_time);
        $this->user->coupons()->attach($coupon->id,['end_time'=>$end_time]);
        return api()->noContent();
    }

    public function money(Request $request){
       if($request->get('money')) {
           if($request->get('is_reny') == 1)
               return api()->collection($this->user->coupons()->where('base_money', '<=', $request->get('money'))->get(), CouponResource::class);
           elseif($request->get('is_reny') == 0)
               return api()->collection($this->user->coupons()->where('base_money', '<=', $request->get('money'))->where('type',0)->get(), CouponResource::class);
       }
       return response()->json(['status'=>0,'msg'=>'参数错误']);
    }
}
