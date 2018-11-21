<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 10:38
 * Function:
 */

namespace App\Services\Auth;


use App\Contracts\JwtAuthContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserAuthWechat;
use Illuminate\Http\Response;
use Tanmo\Wechat\Facades\MiniProgram;

class WeChat implements JwtAuthContract
{
    use Respond;

    /**
     * @var UserAuthWechat
     */
    protected $userAuthWechat;

    /**
     * WeChat constructor.
     * @param UserAuthWechat $userAuthWechat
     */
    public function __construct(UserAuthWechat $userAuthWechat)
    {
        $this->userAuthWechat = $userAuthWechat;
    }

    /**
     * @return Response
     */
    public function login(): Response
    {
        $code = request()->get('code');
        $encryptedData = request()->get('encrypted_data');
        $iv = request()->get('iv');

        ///
        $wechatUser = MiniProgram::app()->auth()->code($code)->encryptedData($encryptedData)->iv($iv)->user();

        ///
        $auth = $this->userAuthWechat->getByOpenId($wechatUser['openId']);
        if (!$auth) {
            /// 未注册
            $user = new User();
            $user->avatarUrl = $wechatUser['avatarUrl'];
            $user->nickname = $wechatUser['nickName'];
            $user->gender = $wechatUser['gender'];
            $user->country = $wechatUser['country'];
            $user->province = $wechatUser['province'];
            $user->city = $wechatUser['city'];
            $user->save();

            ///
            $authWechat = new UserAuthWechat(['open_id' => $wechatUser['openId']]);
            $user->authWechat()->save($authWechat);
        }
        else {
            $user = $auth->user;
            User::where('id', $user->id)
                ->update(['avatarUrl' => $wechatUser['avatarUrl']]);
        }

        $token = auth('api')->login($user);
        return api()->item($user, UserResource::class)->setMeta($this->respondWithToken($token));
    }

    /**
     * @return Response
     */
    public function refresh(): Response
    {
        $user = auth('api')->user();
        return api()->item($user, UserResource::class)->setMeta($this->respondWithToken(auth()->refresh()));
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        auth()->logout();
        return api()->accepted();
    }
}