<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/28
 * Time: 21:41
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\CatResource;
use Illuminate\Support\Facades\DB;

class CatController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * FavoriteController constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {


        return api()->collection($this->user->cats, CatResource::class);
    }

    public function destroy($itemId)
    {
        $this->user->cats()->where('item_id',$itemId)->delete();

        return api()->noContent();
    }

    public function store($itemId,Request $request)
    {
        $cat = $this->user->cats()->where('item_id','=',$itemId)->first();
        if( !isset($cat) && $request->get('count')) {
            $this->user->cats()->create(['item_id'=>$itemId,'count'=>$request->get('count')]);
            return api()->created();
        }else{
            $cat->count = $request->get('count');
            $cat->save();
        }
        return api()->noContent();
    }

    public function update(Cat $cat,Request $request)
    {
        $cat->count = $request->get('count');
        $cat->save();
        return api()->noContent();
    }

}