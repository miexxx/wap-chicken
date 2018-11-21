<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AfterSale;

/**
 * @module 售后服务
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class AfterSaleController extends Controller
{
    /**
     * @permission 编辑查看售后服务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $afterSale = AfterSale::first();

        $header = '售后服务';
        return view('admin::base.afterSale-edit',compact('afterSale','header'));
    }

    /**
     * @permission 修改售后服务
     *
     * @param Request $request
     * @param AfterSale $afterSale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,AfterSale $afterSale)
    {
        $afterSale->content = $request->content;
        $afterSale->save();

        return redirect()->route('admin::afterSales.index');
    }
}
