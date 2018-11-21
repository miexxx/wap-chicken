<?php

namespace App\Admin\Controllers\Recharge;

use App\Models\Recharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @module 充值金额设置
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class RechargeController extends Controller
{
    /**
     * @permission 金额设置列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '优惠金额列表';
        $recharges = Recharge::orderBy('created_at','desc')->paginate(10);
        return view('admin::recharge.recharge',compact('recharges','header'));
    }

    public function create(){
        return view('admin::recharge.recharge-create');
    }

    public function edit(Recharge $recharge){
        return view('admin::recharge.recharge-edit',compact('recharge'));
    }

    public function store(Request $request){
        Recharge::create($request->all(['money','free']));
        return redirect()->route('admin::recharge.index');
    }

    public function destroy(Recharge $recharge){
        $recharge->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }


    /**
     * @permission 修改金额设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Recharge $recharge,Request $request){
       $recharge->money = $request->get('money');
       $recharge->free = $request->get('free');
       $recharge->save();
        return redirect()->route('admin::recharge.index');
    }

}
