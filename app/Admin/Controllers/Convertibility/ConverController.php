<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:48
 * Function:
 */

namespace App\Admin\Controllers\Convertibility;


use App\Http\Controllers\Controller;
use App\Models\Conver;
use App\Models\Freight;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Coupon;
/**
 * @module 实物卷管理
 *
 * Class CategoryController
 * @package App\Admin\Controllers\Items
 */
class ConverController extends Controller
{
    /**
     * @permission 实物卷列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $convers = (new Conver())->orderBy('created_at','desc')->paginate(10);
        $header = '实物卷列表';
        return view('admin::conver.conver',compact('convers','header'));
    }

    public function destroy(Conver $conver){
        $conver->delete();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    public function create($type){
        $time = Conver::TIME;
        while($time--){
            $conver = new Conver();
            $conver->sn = md5(date('Yms')  . rand(10, 99));
            $conver->password =md5(date('Yms')  . rand(100, 200));
            $conver->type = $type;
            $conver->save();
        }
        return redirect()->route('admin::convers.index');
    }

}