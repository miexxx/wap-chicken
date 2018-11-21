<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;
use App\Http\Resources\SupportResource;
use App\Models\Support;
use App\Models\UserSellsale;
use Illuminate\Http\Request;
class SupportController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function show()
    {
        $supports= $this->user->supports;
        return api()->collection($supports,SupportResource::class);
    }

    public function destory(Support $support){

        $support->delete();
        return response()->json(['status'=>1,'msg'=>'删除成功']);
    }

    public function apply(Support $support){
        $first = strtotime($support->created_at);
        $now = strtotime('now');
        $day = (int)round(($now-$first)/3600/24);
        if($day<720){
            return response()->json(['status'=>0,'msg'=>'认养天数不够！']);
        }
        else{
            $count = UserSellsale::where('support_id',$support->id)->count();
            if($count){
                return response()->json(['status'=>1,'msg'=>'请不要重复提交申请！']);
            }
            UserSellsale::create(['user_id'=>$this->user->id,'support_id'=>$support->id]);
            return response()->json(['status'=>1,'msg'=>'代卖申请已提交！']);
        }
    }
    

    
}
