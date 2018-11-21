<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 10:20
 * Function:
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
use App\Models\UserAuthWechat;
use App\Models\User;
use App\Models\UserCode;
use Auth;

class AuthController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login(Request $request,UserCode $userCode = null)
    {
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        $response = $app->oauth->scopes(['snsapi_userinfo'])->redirect(route('oauth_callback',$userCode).'?path='.$request->get('state'));

        return $response;
    }

    public function oauth_callback(UserCode $userCode = null,Request $request)
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
            if($userCode && $userCode->user->is_shared) {
                $user->parent_id = $userCode->user->id;
            }
            $user->save();

            ///
            $authWechat = new UserAuthWechat(['open_id' => $wechatUser->id]);
            $user->authWechat()->save($authWechat);
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->save();

        }
        else {
            $user = $auth->user;
            User::where('id', $user->id)
                ->update(['avatarUrl' => $wechatUser->avatar]);
        }

        $token = Auth::guard('api')->fromUser($user);

        $url = $request->get('path');

        if(!$url) {
             $url = '/app/home';
        }
        return redirect($url)->cookie('access_token',$token,\Auth::guard('api')->factory()->getTTL(),"","",false,false);

//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60,
//            //'redirect_url' => $request->get('url'),
//        ]);
}

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return api()->noContent();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $token = Auth::guard('api')->refresh();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    public function shared($code)
    {
        $userCode = UserCode::where('code',$code)->first();
        if($userCode) {
            return redirect()->route('login',$userCode);
        }
        else {
            return response()->json(['message' => '访问的页面不存在!']);
        }
    }

    public function callback()
    {
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        $response = $app->server->serve();

        return $response;
    }
}