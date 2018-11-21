<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Distribution;

/**
 * @module 配送说明
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class DistributionController extends Controller
{
    /**
     * @permission 编辑查看配送说明
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $distribution = Distribution::first();

        $header = '配送说明';
        return view('admin::base.distribution-edit',compact('distribution','header'));
    }

    /**
     * @permission 修改配送说明
     *
     * @param Request $request
     * @param Distribution $distribution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Distribution $distribution)
    {
        $distribution->content = $request->content;
        $distribution->save();

        return redirect()->route('admin::distributions.index');
    }
}
