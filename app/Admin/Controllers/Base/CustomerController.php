<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

/**
 * @module 大客户
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class CustomerController extends Controller
{
    /**
     * @permission 编辑查看大客户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customer = Customer::first();

        $header = '大客户';
        return view('admin::base.customer-edit',compact('customer','header'));
    }

    /**
     * @permission 修改大客户
     *
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Customer $customer)
    {
        $customer->content = $request->content;
        $customer->save();

        return redirect()->route('admin::customers.index');
    }
}
