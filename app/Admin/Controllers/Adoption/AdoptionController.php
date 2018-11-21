<?php

namespace App\Admin\Controllers\Adoption;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @module 认养规则设置
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class AdoptionController extends Controller
{
    /**
     * @permission 认养年限与价格
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $year = getenv('MAINTENANCE_YEARS');
        $money = getenv('MAINTENANCE_MONEY');
        $header = '认养年限与价格';
        return view('admin::adoptions.adoption',compact('year','money','header'));
    }

    /**
     * @permission 认养年限可提现鸡蛋数
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        $eggs = getenv('PUT_EGGS');
        $header = '认养年限可提现鸡蛋数';
        return view('admin::adoptions.egg',compact('eggs','header'));
    }

}
