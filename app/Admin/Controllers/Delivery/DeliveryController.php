<?php

namespace App\Admin\Controllers\Delivery;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\About;
/**
 * @module 分期配送
 *
 * Class AboutController
 * @package App\Admin\Controllers\Base
 */
class DeliveryController extends Controller
{
    /**
     * @permission 分期配送设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $wdeliverys= (new Delivery())->orderBy('sort','desc')->where('type','=',0)->get();
        $mdeliverys= (new Delivery())->orderBy('sort','desc')->where('type','=',1)->get();
        $wtimes =  (new Delivery())->orderBy('sort','desc')->where('type','=',2)->get();
        $mtimes =  (new Delivery())->orderBy('sort','desc')->where('type','=',3)->get();
        return view('admin::delivery.delivery',compact('wdeliverys','mdeliverys','wtimes','mtimes'));
    }
    public function store(Request $request){
        Delivery::truncate();
        $weeks = $request->get('wnumber');
        $months = $request->get('mnumber');
        $wtime = $request->get('wtime');
        $mtime = $request->get('mtime');
        if($weeks) {
            foreach ($weeks as $week) {
                if($week) {
                    $delivery = (new Delivery());
                    $delivery->number = $week;
                    $delivery->type = 0;
                    $delivery->save();
                }
            }
        }
        if($months) {
            foreach ($months as $month) {
                if($month) {
                    $delivery = (new Delivery());
                    $delivery->number = $month;
                    $delivery->type = 1;
                    $delivery->save();
                }
            }
        }

        foreach ($wtime as $wt) {
            $delivery = (new Delivery());
            $delivery->number = $wt;
            $delivery->type = 2;
            $delivery->save();
        }
        foreach ($mtime as $mt) {
            $delivery = (new Delivery());
            $delivery->number = $mt;
            $delivery->type = 3;
            $delivery->save();
        }

    }


}
