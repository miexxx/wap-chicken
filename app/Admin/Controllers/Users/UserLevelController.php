<?php

namespace App\Admin\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserLevel;

/**
 * @module 分享等级
 *
 * Class UserLevelController
 * @package App\Admin\Controllers\Users
 */
class UserLevelController extends Controller
{
    /**
     * @permission 分享等级列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '分享等级列表';
        $userLevels = UserLevel::orderBy('level','asc')->paginate(10);

        return view('admin::users.userLevels-index',compact('header','userLevels'));
    }

    /**
     * @permission 编辑分享等级-页面
     *
     * @param UserLevel $userLevel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(UserLevel $userLevel)
    {
        $header = '编辑分享等级';

        return view('admin::users.userLevels-edit',compact('header','userLevel'));
    }

    /**
     * @return string
     */
    public function checkName()
    {
        $name = request()->get('name');
        $current_name = request()->get('current_name');

        if($current_name || $current_name == '0') {
            if( $current_name == $name) {
                return '{"valid":true}';
            }
        }

        $userLevel = UserLevel::where('name','=',$name)->first();
        if($userLevel) {
            return '{"valid":false}';
        }
        return '{"valid":true}';
    }

    /**
     * @return string
     */
    public function checkLevel(){
        $level = request()->get('level');
        $current_level = request()->get('current_level');

        if($current_level || $current_level == '0') {
            if( $current_level == $level) {
                return '{"valid":true}';
            }
        }

        $userLevel = UserLevel::where('level','=',$level)->first();
        if($userLevel) {
            return '{"valid":false}';
        }
        return '{"valid":true}';
    }

    /**
     *@permission 编辑分享等级
     *
     * @param Request $request
     * @param UserLevel $userLevel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,UserLevel $userLevel)
    {
        $userLevel->name = $request->get('name');
        $userLevel->money = $request->get('money');
        $userLevel->level = $request->get('level');
        $userLevel->sales_percent = $request->get('sales_percent');
        $userLevel->invite_percent = $request->get('sales_percent');

        $userLevel->save();
        return redirect()->route('admin::userLevels.index');
    }
}
