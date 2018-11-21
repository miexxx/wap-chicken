<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\StorehouseResource;
use App\Http\Resources\StoreRecordResource;
use App\Models\About;
use App\Models\Conver;
use App\Models\Item;
use App\Models\Order;
use App\Models\Storehouse;
use App\Models\StoreRecord;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;

class StorehouseController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }
    public function egg(){
        $eggs = $this->user->eggs;
        return api()->collection($eggs, StorehouseResource::class);
    }
    public function chicken(){
        $chickens = $this->user->chickens;
        return api()->collection($chickens, StorehouseResource::class);
    }

    public function record(){
        $storerecord = StoreRecord::where('user_id',$this->user->id)->get();
        return api()->collection($storerecord,StoreRecordResource::class);
    }

    public function distribute(Storehouse $storehouse,Request $request){
        $count = $request->get('count');
        if($count > $storehouse->num ){
            return response()->json(['status'=>0,'msg'=>'仓库数量不足']);
        }
        $address = $request->all(['user_name', 'postal_code', 'tel', 'detail']);
        $remark = $request->get('remark', '');
        $type =$request->get('type');
        $order =(new Order())->submitStoreHouse($this->user, $storehouse->item,$count, $address, $remark,$type);
        if($order){
            StoreRecord::create([
                'user_id'=>$this->user->id,
                'store_id'=>$storehouse->id,
                'type'=>StoreRecord::DELIVE,
                'count'=>$count,
            ]);
            $storehouse->num-=$count;
            $storehouse->save();
            if($storehouse->num<=0)
                $storehouse->delete();
        }
        return api()->item($order,OrderResource::class);
    }

    public function conver(Request $request){
        $sn = $request->get('sn');
        $password = $request->get('password');
        if($sn ==null ||$password ==null)
            return response()->json(['status'=>0,'msg'=>'参数不足！']);
        $conver = Conver::where('sn',$sn)->where('password',$password)->first();
        if($conver){
            $item = Item::where('item_type',$conver->type)->first();
            if($item && $item->stock){
                $item->stock-=1;
                if($item->item_type == 1 || $item->item_type == 4){
                    //鸡
                    if (Storehouse::where('item_id', $item->id)->where('user_id', $this->user->id)->count() == 0)
                        $this->user->chickens()->create(['item_id' => $item->id, 'type' => Storehouse::CHINCKEN,'num'=>1]);
                    else {
                        $chicken = Storehouse::where('item_id', $item->id)->where('user_id', $this->user->id)->first();
                        $chicken->num += 1;
                        $chicken->save();
                    }
                } elseif ($item->item_type == 2||$item->item_type == 3) {
                    //蛋
                    if (Storehouse::where('item_id', $item->id)->where('user_id', $this->user->id)->count() == 0)
                        $this->user->chickens()->create(['item_id' => $item->id, 'type' => Storehouse::EGG,'num'=>1]);
                    else {
                        $egg = Storehouse::where('item_id', $item->id)->where('user_id', $this->user->id)->first();
                        $egg->num += 1;
                        $egg->save();
                    }
                }
                $item->save();
                $conver->delete();
                return response()->json(['status'=>1,'msg'=>'领取成功！']);
            }
            else{
                return response()->json(['status'=>0,'msg'=>'库存不足！']);
            }
        }
        return response()->json(['status'=>0,'msg'=>'兑换卷不存在！']);
    }

    public function senduser(Storehouse $storehouse,Request $request){
        $phone  =$request->get('phone');
        $count = $request->get('count');
        if($storehouse->num <$count || $phone==null ||$count==null){
            return response()->json(['status'=>0,'msg'=>'数据错误']);
        }
        $user = User::where('phone',$phone)->first();
        if($user == null){
            return response()->json(['status'=>0,'msg'=>'该用户未绑定手机号']);
        }
        for($i=0;$i<$count;$i++)
            Support::create(['item_id' => $storehouse->item->id, 'egg_num' => getenv('PUT_EGGS'), 'can_num' => 0,'user_id'=>$user->id]);
        StoreRecord::create([
            'user_id'=>$this->user->id,
            'store_id'=>$storehouse->id,
            'type'=>StoreRecord::SEND,
            'count'=>$count,
            'touser'=>$user->id
        ]);
        $storehouse->num-=$count;
        $storehouse->save();
        if($storehouse->num<=0)
            $storehouse->delete();
        return response()->json(['status'=>1,'msg'=>'赠送成功']);
    }
}
