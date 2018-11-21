<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SellsaleController extends Controller
{

    public function index()
    {
        $egg = getenv('EGG_PROFIT');
        $chicken = getenv('CHICKEN_PROFIT');
        return response()->json(['egg'=>$egg,'chicken'=>$chicken]);
    }

}
