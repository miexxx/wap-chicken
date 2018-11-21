<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recharge;


class RechargeController extends Controller
{

    public function index()
    {
       $recharges = Recharge::orderBy('created_at','desc')->get(['id','money','free']);
        return response()->json($recharges);
    }


}
