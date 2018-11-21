<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;

/**
 * @module 模板消息通知
 *
 * Class TemplateController
 * @package App\Admin\Controllers\Base
 */
class TemplateController extends Controller
{
    /**
     * @permission 模板列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '消息通知列表';
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());

        $templates = $app->template_message->getPrivateTemplates()['template_list'];

        //file_put_contents('text.txt',var_export($templates,1),FILE_APPEND);
//        $app->template_message->send([
//            'touser' => 'oqjOl1GkT3HAiNveQ44upyWUaiV8',
//            'template_id' => 'cl-wSw8oeTAxgQBR6Ixii3zahelgvxqyMK94Hb6Ctqs',
//            'url' => 'http://www.baidu.com',
//            'data' => [
//                'first' => ['感谢你购买我们的商品','#ff0000'],
//                'a' => '微信公众号的测试:模板消息的使用',
//                'b' => ['value'=>'价格：50元','color'=>'#00ff00'],
//                'remark' => '如果商品出现什么问题，请及时联系我们!',],
//        ]);
        return view('admin::base.templates-templates',compact('templates'));
    }
}