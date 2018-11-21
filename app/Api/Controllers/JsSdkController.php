<?php

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use App\Http\Resources\JsSdkResource;

class JsSdkController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function getSdk()
    {
        $app =  Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        //需要调用的接口列表
        //$APIs = array('onMenuShareQQ', 'onMenuShareWeibo');
        $url = request()->get('url');

        $app->jssdk->setUrl($url);

        $config = $app->jssdk->buildConfig([],true,false,false);
        $data = ['appId' => $config['appId'],'nonceStr' => $config['nonceStr'],'timestamp' => $config['timestamp'],'signature' => $config['signature']];

        return response()->json($data);
    }
}