<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAuthWechat;
use App\Models\UserCode;
use EasyWeChat\Factory;
use App\Models\User;

class HomeController extends Controller
{
    public function home(UserCode $userCode = null)
    {
        return view('index');
    }

    public function oauth_callback(UserCode $userCode = null)
    {
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        $wechatUser = $app->oauth->user();

        $auth = UserAuthWechat::where('open_id',$wechatUser->id)->first();

        if (!$auth) {
            /// 未注册
            $user = new User();
            $user->avatarUrl = $wechatUser->avatar;
            $user->nickname = $wechatUser->nickname;
            $user->gender = $wechatUser->getOriginal()['sex'];
            $user->country = $wechatUser->getOriginal()['country'];
            $user->province = $wechatUser->getOriginal()['province'];
            $user->city = $wechatUser->getOriginal()['city'];
            if($userCode) {
                $user->parent_id = $userCode->user->id;
            }
            $user->save();

            ///
            $authWechat = new UserAuthWechat(['open_id' => $wechatUser->id]);
            $user->authWechat()->save($authWechat);
        }
        else {
            $user = $auth->user;
            User::where('id', $user->id)
                ->update(['avatarUrl' => $wechatUser->avatar]);
        }

        return view('index');
    }
}
