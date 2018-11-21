<?php

namespace App\Admin\Controllers\Sellsale;

use App\Models\UserSellsale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @module 委托代卖设置
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class SellsaleController extends Controller
{
    /**
     * @permission 委托代卖详情
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $egg = getenv('EGG_PROFIT');
        $chicken = getenv('CHICKEN_PROFIT');
        $header = '委托代卖';
        return view('admin::sellsale.sellsale',compact('egg','chicken'));
    }

    /**
     * @permission 修改委托代卖
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request){
        $egg = $request->get('egg');
        $chicken = $request->get('chicken');
        if($egg&&$chicken) {
            edit_env(['EGG_PROFIT' => $egg, 'CHICKEN_PROFIT' => $chicken]);
            return redirect()->route('admin::sellsale.index');
        }
        return redirect()->route('admin::sellsale.index');
    }

    /**
     * @permission 委托代卖申请列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply(){
        $sellsales = UserSellsale::orderBy('created_at','desc')->paginate(10);
        return view('admin::sellsale.sellsale-apply',compact('sellsales'));
    }

    public function success(UserSellsale $userSellsale){
        //todo
        $userSellsale->status = 1;
        $userSellsale->save();
        return response()->json(['status' => 1, 'message' => '操作成功']);
    }


    public function destroy(UserSellsale $userSellsale){
        $userSellsale->delete();
        return response()->json(['status' => 1, 'message' => '删除成功']);
    }

}
