<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserCode;
use App\Models\Coupon;
use App\Http\Requests\UserInfoRequest;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class UserController extends Controller
{
    /**
     * @param UserInfoRequest $request
     * @return \Tanmo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $phone= $request->get('phone');
        $count =User::where('phone',$phone)->count();
        if($count){
            return response()->json(['status'=>0,'msg'=>'该手机已经被绑定']);
        }
        if($phone==null || preg_match('/^1[34578]\d{9}$/',$phone) == false){
            return response()->json(['status'=>0,'msg'=>'手机格式不正确']);
        }
        $user->phone = $phone;
        $user->save();

        return api()->item($user, UserResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function show()
    {
        $user = auth()->user();
        return api()->item($user, UserResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sharer()
    {
        $user = auth()->user();
        if(!$user->is_shared) {
            $user->is_shared = 1;
            $user->shared_at = date('Y-m-d H:i:s');
            $user->stt_at = date('Y-m-d H:i:s',strtotime("+22 day"));
            $user->save();
            return response()->json(['status'=>1,'message'=>'申请成功']);
        }
        else {
            return response()->json(['status'=>0,'message'=>'申请失败，你已是分享者']);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function inviteLink()
    {
        $user = auth()->user();
        $this->authorize('share',$user);

        $userCode = $user->userCode()->first();

        //$app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        if(!$userCode) {
            $userCode = $this->makeCode($user);
        }

       // $result = $app->qrcode->temporary("http://www.fujianqiuge.com/app/home/$code", 7 * 24 * 3600);
       // $url = $app->qrcode->url($result['ticket']);

        return response()->json(['url'=>route('shared',$userCode->code),'code' => $userCode->code_url]);
    }

    /**
     * @param User $user
     * @return string
     */
    protected function makeCode(User $user)
    {
        $invitedCount = User::where('parent_id', '=', $user->id)->count();
        $str = md5($user->id . '_' . $invitedCount . '_' . time() . rand(100, 999));

        $code = mb_substr($str, 0,6);

        if(!file_exists(public_path('storage/shared/'))){
            mkdir(public_path("storage/shared"));
        }
        QrCode::format('png')->size(200)->encoding('UTF-8')->generate(route('shared',$code),public_path('storage/shared/'.$str.'.png'));

        if (UserCode::where('code', '=', $code)->count()) {
            $code = $this->makeCode($user);
        }
        $userCode = new UserCode(['code' => $code,'code_url' => '/shared/'.$str.'.png' ]);
        $userCode = $user->userCode()->save($userCode);

        return $userCode;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkInviteNum()
    {
        $user = auth()-> user();
        if($user->invite_bonus) {
            return response()->json(['status' =>0 ,'message' => '每人限领一次哦！']);
        }

        if(count($user->childrens)>=3)
        {
            $user->invite_bonus = 1;
            $res = $user->save();

            if($res) {
                $user->wallet->money += 20;
                $user->wallet->save();
            }

            return response()->json(['status' =>1 ,'message' => '恭喜你,领取成功，请到我的余额中查看！']);
        }
        return response()->json(['status' => 0,'message' => '啊哦，你还未达到领取条件哦！']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkInviteSale()
    {
        $user = auth()->user();

        if($user->iv_sale_bonus) {
            return response()->json(['status' =>0 ,'message' => '每人限领一次哦！']);
        }

        $num = $user->childrens()->whereHas('orders',function ($query){
            $query->whereIn('status',[2,4]);
        })->count();

        if($num>0) {

            $user->iv_sale_bonus = 1;
            $res = $user->save();

            if($res) {
                $user->wallet->money += 30;
                $user->wallet->save();
            }

            return response()->json(['status' =>1 ,'message' => '恭喜你,领取成功，请到我的余额中查看！']);
        }
        return response()->json(['status' => 0,'message' => '啊哦，你还未达到领取条件哦！']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareGetCoupon()
    {
        $user = auth()->user();

        $coupon = Coupon::where('type',1)->first();

        $end_time = date("Y-m-d H:i:s",strtotime("+".$coupon->time."day"));
        //dd($end_time);
        $user->coupons()->attach($coupon->id,['end_time'=>$end_time]);

        return response()->json(['status' =>1,'message'=> '优惠券已发送到卡券包中！']);
    }
}