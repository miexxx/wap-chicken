<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class AdoptionController extends Controller
{

    public function index()
    {
        $year = getenv('MAINTENANCE_YEARS');
        $money = getenv('MAINTENANCE_MONEY');
        return response()->json(['year'=>$year,'money'=>$money]);
    }

    public function show(){
        $eggs=getenv('PUT_EGGS');
        return response()->json(['eggs'=>$eggs]);
    }


}
