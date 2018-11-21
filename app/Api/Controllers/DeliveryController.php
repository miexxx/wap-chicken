<?php

namespace App\Api\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\About;

class DeliveryController extends Controller
{
    /**
     * @permission 分期配送设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $wdeliverys= (new Delivery())->orderBy('sort','desc')->where('type','=',0)->get(['number']);
        $mdeliverys= (new Delivery())->orderBy('sort','desc')->where('type','=',1)->get(['number']);
        $wtimes = (new Delivery())->orderBy('sort','desc')->where('type','=',2)->get(['number']);
        $mtimes =(new Delivery())->orderBy('sort','desc')->where('type','=',3)->get(['number']);
        return response()->json(['week'=>$wdeliverys,'month'=>$mdeliverys,'week_time'=>$wtimes,'month_time'=>$mtimes]);
    }



}
