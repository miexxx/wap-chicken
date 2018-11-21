<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:48
 * Function:
 */

namespace App\Admin\Controllers\Items;


use App\Http\Controllers\Controller;
use App\Models\Freight;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Coupon;
/**
 * @module 优惠券管理
 *
 * Class CategoryController
 * @package App\Admin\Controllers\Items
 */
class CouponController extends Controller
{
    /**
     * @permission 优惠卷列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $coupons = (new Coupon())->orderBy('created_at', 'desc')->paginate(10);

        $header = '优惠卷列表';
        return view('admin::items.coupons', compact('coupons','header'));
    }

    /**
     * @permission 新增优惠卷-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::items.coupon-create');
    }

    /**
     * @permission 新增优惠卷
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
       $coupon = new Coupon();
       $coupon->money = $request->get('money');
       $coupon->base_money = $request->get('base_money');
       $coupon->time = $request->get('time');
       $coupon->save();
        return redirect()->route('admin::coupons.index');
    }

    /**
     * @permission 修改优惠卷-页面
     *
     * @param ItemCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Coupon $coupon)
    {
        return view('admin::items.coupon-edit', compact('coupon'));
    }

    /**
     * @permission 修改优惠卷
     *
     * @param ItemCategory $category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Coupon $coupon, Request $request)
    {
        $coupon->money = $request->get('money');
        $coupon->base_money = $request->get('base_money');
        $coupon->time = $request->get('time');
        $coupon->save();

        if($coupon->type == 1) {
            return response()->json(['status' =>1,'message' => '修改成功！']);
        }

        return redirect()->route('admin::coupons.index');
    }

    /**
     * @permission 删除优惠卷
     *
     * @param ItemCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * @permission 分享朋友圈优惠券
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inviteCoupon()
    {
        $header = '分享朋友圈优惠券';
        $coupon = Coupon::where('type', 1)->first();

        return view('admin::items.inviteCoupon',compact('header','coupon'));
    }

}