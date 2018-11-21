<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:48
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Freight;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;


class FreightController extends Controller
{

    public function index()
    {

        $freights = (new Freight())->orderBy('created_at', 'desc')->select('free','freight')->paginate(10);

        return response()->json($freights);
    }


}