<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdoptRule;

/**
 * @module 基础设置
 *
 * Class AdoptRuleController
 * @package App\Admin\Controllers\Base
 */
class AdoptRuleController extends Controller
{
    /**
     * @permission 认养规则
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $header = '认养规则';
        $adoptRule = AdoptRule::first();

        return view('admin::base.adoptRule-index',compact('adoptRule','header'));
    }

    /**
     * @permission 编辑价格
     *
     * @param Request $request
     * @param AdoptRule $adoptRule
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function update(AdoptRule $adoptRule,Request $request)
    {
        $adoptRule->price = $request->get('price');

        $adoptRule->save();

        return response()->json(['status' => 1,'message' => '修改成功']);
    }
}
