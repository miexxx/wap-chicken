<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 9:55
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use App\Models\Home;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
class HomeController extends Controller
{
    /**
     * 首页
     *
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        return api()->item(new Home(), HomeResource::class);
    }


    public function banner()
    {
        $banners = Banner::open()->orderBy('order', 'desc')->get();
        return BannerResource::collection($banners);
    }
}