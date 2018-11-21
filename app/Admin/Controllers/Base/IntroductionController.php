<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Introduction;

/**
 * @module 企业简介
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class IntroductionController extends Controller
{
    /**
     * @permission 编辑查看企业简介
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $introduction = Introduction::first();

        $header = '企业简介';
        return view('admin::base.introduction-edit',compact('introduction','header'));
    }

    /**
     * @permisssion 修改企业简介
     *
     * @param Request $request
     * @param Introduction $introduction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Introduction $introduction)
    {
        $introduction->content = $request->content;

        $introduction->save();

        return redirect()->route('admin::introductions.index');
    }
}
