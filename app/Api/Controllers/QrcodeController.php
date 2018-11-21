<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;
use App\Models\Qrcodes;
use Illuminate\Http\Request;

class QrcodeController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user  = auth()->user();
    }

    public function getmoney(Request $request)
    {
        $sn  = $request->get('sn');
        $password = $request->get('password');
        if($sn ==null ||$password == null)
            return response()->json(['status'=>0,'msg'=>'参数不完整']);
        $qrcode = Qrcodes::where('sn',$sn)->where('password',$password)->first();
        if($qrcode && $this->user){
            $wallet = $this->user->wallet;
            if($qrcode->type == 0){
                $wallet->money+=Qrcodes::ONE;
            }
            elseif($qrcode->type == 1){
                $wallet->money+=Qrcodes::FIVE;
            }
            $qrcode->delete();
            $wallet->save();
            return response()->json(['status'=>1,'msg'=>'兑换成功']);
        }
        return response()->json(['status'=>0,'msg'=>'优惠卷无效']);
    }
}
