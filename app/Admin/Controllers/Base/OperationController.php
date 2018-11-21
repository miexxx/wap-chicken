<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operation;

/**
 * @module 运营模式
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class OperationController extends Controller
{
    /**
     * @permission 编辑查看运营模式
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $operation = Operation::first();

        $header = '运营模式';
        return view('admin::base.operation-edit',compact('operation','header'));
    }

    /**
     * @permisssion 修改运营模式
     *
     * @param Request $request
     * @param Introduction $introduction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Operation $operation)
    {
        $operation->content = $request->content;
        $operation->save();

        return redirect()->route('admin::operations.index');
    }
}
