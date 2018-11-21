<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 16:01
 * Function:
 */

namespace App\Api\Controllers;


use App\Exceptions\LowStocksException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSubmitRequest;
use App\Http\Resources\ExpressResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderStageResource;
use App\Http\Resources\RgRecordResource;
use App\Models\Freight;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use App\Models\OrderWechatPayment;
use App\Models\Storehouse;
use App\Models\StoreRecord;
use App\Models\Support;
use App\Models\User;
use App\Models\UserPromote;
use App\Models\UserInvite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    /**
     * 所有订单
     */
    public function index()
    {
        $user = auth()->user();
        $status = request()->get('status');
        if(isset($status))
            $orders = Order::filterUserId($user->id)->filterStatus($status)->latest()->paginate(10);
        else{
            $orders = Order::filterUserId($user->id)->latest()->paginate(10);
        }

        return api()->collection($orders, OrderResource::class);
    }

    //分期配送订单
    public function stageindex(){
        $user = auth()->user();
        $orders =Order::filterUserId($user->id)->where('type',1)->latest()->paginate(10);
        return api()->collection($orders, OrderStageResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 订单状态统计
     */
    public function orderstatus(){
        $user = auth()->user();
        $payment = $ship = $receipt = $comment = $finish = $refund =$stage= 0;
        $order_status = Order::where('user_id',$user->id)->select('status','refund','type')->get();
        foreach ($order_status as $arr){
            if ($arr['refund'] == 1){
                $refund++;
                continue;
            }
            switch ($arr['status']){
                case 0:
                    $payment++;
                    break;
                case 1:
                    $ship++;
                    break;
                case 2:
                    $receipt++;
                    break;
                case 3:
                    $comment++;
                    break;
                case 4:
                    $finish++;
                    break;
            }
            if($arr['type']==1){
                $stage ++;
            }
        }
        $data = array('payment'=>$payment,'Ship'=>$ship,'Receipt'=>$receipt,'finish'=>$finish,'stage'=>$stage);
        return response()->json(['data' => $data]);
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Order $order)
    {
        $this->authorize('show', $order);

        return api()->item($order, OrderDetailResource::class);
    }

    /**
     * 提交订单
     *
     * @param OrderSubmitRequest $request
     * @return \Tanmo\Api\Http\Response
     */
    public function store(OrderSubmitRequest $request)
    {
        $address = $request->all(['user_name', 'postal_code', 'tel', 'detail']);
        $remark = $request->get('remark', '');
        $content = $request->get('content');
        $type = $request->get('type');
        $coupon_id = $request->get('coupon_id');
        $is_reny = $request->get('is_reny');
        $reny_id = $request->get('reny_id');
        $items = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return api()->error('Submission of data errors', 400);
        }
        foreach ($address as $value){
            if($value === null) {
                $address = null;
                break;
            }
        }
        $order = (new Order())->submit(auth()->user(), $items, $address, $remark, $type, $coupon_id, $is_reny,$reny_id);

        return api()->item($order, OrderResource::class)->created();
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Order $order)
    {
        $this->authorize('destroy', $order);

        $order->delete();

        return api()->noContent();
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    function express(Order $order)
    {
        $this->authorize('express', $order);

        return api()->item($order, ExpressResource::class);
    }

    public function walletpay(Order $order){
        if($order->status != Order::NONAL_TYPE)
            return response()->json(['status'=>0,'msg'=>'订单异常']);
        $user = $order->user;
        $allprice = isset($order->coupon)?$order->coupon->money:0;
        if($user->wallet->money < $order->price-$allprice){
            return response()->json(['status'=>0,'msg'=>'余额不足！']);
        }
        $user->wallet->money-=$order->price-$allprice;
        $user->wallet->save();
        $order->paid_at = Carbon::now();


        if($order->type == Order::RECHARGE_TYPE){

            $wallet = $user->wallet;
            $wallet->money+= $order->items_price;
            $wallet->save();
            $order->status = Order::FINISH;
        }else{
            $items = $order->items;
            foreach ($items as $item){
                if($item->type === OrderItem::CANK_TYPE){
                    if($item->item->item_type == 1 ||$item->item->item_type == 4){
                        //鸡
                        if(Storehouse::where('item_id',$item->item_id)->where('user_id',$user->id)->count() == 0)
                            $chicken = $user->chickens()->create(['item_id'=>$item->item_id,'type'=>Storehouse::CHINCKEN,'num'=>$item->count]);
                        else{
                            $chicken =Storehouse::where('item_id',$item->item_id)->where('user_id',$user->id)->first();
                            $chicken->num+=$item->count;
                            $chicken->save();
                        }
                        StoreRecord::create([
                            'user_id'=>$user->id,
                            'store_id'=>$chicken->id,
                            'type'=>StoreRecord::BUY,
                            'count'=>$item->count,
                        ]);

                    }
                    elseif($item->item->item_type == 2||$item->item->item_type == 3){
                        //蛋
                        if(Storehouse::where('item_id',$item->item_id)->where('user_id',$user->id)->count() == 0)
                            $egg = $user->chickens()->create(['item_id'=>$item->item_id,'type'=>Storehouse::EGG,'num'=>$item->count]);
                        else{
                            $egg =Storehouse::where('item_id',$item->item_id)->where('user_id',$user->id)->first();
                            $egg->num+=$item->count;
                            $egg->save();
                        }
                        StoreRecord::create([
                            'user_id'=>$user->id,
                            'store_id'=>$egg->id,
                            'type'=>StoreRecord::BUY,
                            'count'=>$item->count,
                        ]);
                    }
                }
                elseif($item->type === OrderItem::RENY_TYPE){
                    for($i = 0;$i<$item->count;$i++)
                        $user->supports()->create(['item_id'=>$item->item_id,'egg_num'=>getenv('PUT_EGGS'),'can_num'=>0]);
                }

                if($reny_id = $order->reny_id ){
                    $support = Support::where('id',$reny_id)->first();
                    if($support->egg_num<$item->count){
                        return response()->json(['status'=>0,'msg'=>'认养鸡的蛋不够了！']);
                    }
                    $support->egg_num-=$item->count;
                    $support->save();
                    break;
                }
            }
            $order->status = Order::WAIT_DELIVER;
        }

        $coupon = $order->coupon;
        if($coupon) {
            DB::table('user_coupons')->where('user_id',$user->id)->where('coupon_id',$coupon->id)->limit(1)->delete();
            $order->price = $order->price-$order->coupon->money;
        }
        $order->save();
        return response()->json(['status'=>1,'msg'=>'支付成功！']);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function pay(Order $order)
    {
        $user = auth()->user();
        $config = app('wechat.payment.default')->config->toArray();
        $app = Factory::payment($config);

        $result = $app->order->unify([
            'body' => config('app.name') . '-订单:' . $order->sn,
            'out_trade_no' => $order->sn,
            'total_fee' => ($order->price-(isset($order->coupon)?$order->coupon->money:0)) * 100,
            //'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => url()->route('wechat.paid_notify'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $user->authWechat->open_id,
        ]);

        //$result = json_decode($result,true);
        $data = [
            'appId' => $config['app_id'],
            'timeStamp' => (string)time(),
            'nonceStr' => $result['nonce_str'],
            'package' => 'prepay_id=' . $result['prepay_id'],
            'signType' => 'MD5'
        ];

        $data['paySign'] = gen_sign($data,$config['key']);
        $data['sign'] = $result['sign'];
        unset($data['appId']);

        return response()->json(['data' => $data]);
    }

    /**
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function confirm(Order $order)
    {
        $this->authorize('confirm', $order);

        $order->status = Order::FINISH;
        $order->confirmed_at = Carbon::now();
        $order->save();

        return api()->noContent();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     *
     */
    public function paidNotify()
    {
        $config = app('wechat.payment.default')->config->toArray();
        $app = Factory::payment($config);
        $response = $app->handlePaidNotify(function ($message,$fail){
            Log::notice(json_encode($message));

            //找到订单
            $order = Order::filterSn($message['out_trade_no'])->first();

            //订单不存在或已支付
            if (!$order || $order->paid_at) {
                return true; //告诉微信，别再通知我
            }

            //通信状态
            if ($message['return_code'] === 'SUCCESS') {
                //支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $user = $order->user;
                    $order->paid_at = Carbon::now();

                    if ($order->type == Order::RECHARGE_TYPE) {
                        $wallet = $user->wallet;
                        $wallet->money += $order->items_price;
                        $wallet->save();
                        $order->status = Order::FINISH;
                    } else {
                        $items = $order->items;

                        foreach ($items as $item){

                            if($item->type === OrderItem::CANK_TYPE){
                                if($item->item->item_type == 1 || $item->item->item_type == 4){
                                    //鸡
                                    if (Storehouse::where('item_id', $item->item_id)->where('user_id', $user->id)->count() == 0)
                                        $chicken = $user->chickens()->create(['item_id' => $item->item_id, 'type' => Storehouse::CHINCKEN,'num'=>$item->count]);
                                    else {
                                        $chicken = Storehouse::where('item_id', $item->item_id)->where('user_id', $user->id)->first();
                                        $chicken->num += $item->count;
                                        $chicken->save();
                                    }
                                    StoreRecord::create([
                                        'user_id'=>$user->id,
                                        'store_id'=>$chicken->id,
                                        'type'=>StoreRecord::BUY,
                                        'count'=>$item->count,
                                    ]);
                                } elseif ($item->item->item_type == 2||$item->item->item_type == 3) {
                                    //蛋
                                    if (Storehouse::where('item_id', $item->item_id)->where('user_id', $user->id)->count() == 0)
                                        $egg = $user->chickens()->create(['item_id' => $item->item_id, 'type' => Storehouse::EGG,'num'=>$item->count]);
                                    else {
                                        $egg = Storehouse::where('item_id', $item->item_id)->where('user_id', $user->id)->first();
                                        $egg->num += $item->count;
                                        $egg->save();
                                    }
                                    StoreRecord::create([
                                        'user_id'=>$user->id,
                                        'store_id'=>$egg->id,
                                        'type'=>StoreRecord::BUY,
                                        'count'=>$item->count,
                                    ]);
                                }
                            }
                            elseif ($item->type === OrderItem::RENY_TYPE) {
                                for($i = 0;$i<$item->count;$i++)
                                    $user->supports()->create(['item_id' => $item->item_id, 'egg_num' => getenv('PUT_EGGS'), 'can_num' => 0]);
                            }

                            if($reny_id = $order->reny_id){
                                $support = Support::where('id',$reny_id)->first();
                                $support->egg_num-=$item->count;
                                $support->save();
                                break;
                            }
                        }
                        $order->status = Order::WAIT_DELIVER;
                    }
                    $orderWechatPayment = new OrderWechatPayment(['sn' => $message['transaction_id']]);
                    $order->wechatPayment()->save($orderWechatPayment);

                    $coupon = $order->coupon;
                    if ($coupon) {
                        DB::table('user_coupons')->where('user_id',$user->id)->where('coupon_id',$coupon->id)->limit(1)->delete();
                        $order->price = $order->price - $order->coupon->money;
                    }
                } else {
                    Log::error('用户支付失败，SN:' . $message['out_trade_no']);
                }
            } else {
                    return $fail('通信失败，请稍后再通知我');
                }
            $order->save();

            return true;
        });

        return $response;
    }

    public function statement(Request $request){
        $content = $request->get('content');
        $is_reny = $request->get('is_reny');
        $items = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return api()->error('Submission of data errors', 400);
        }
        $price = 0;
        $freight = 0;
        $item_contents = array();
        foreach ($items as $item) {
            $itemInfo = Item::where('id', $item['id'])->lockForUpdate()->first();
            if(isset($is_reny)&&$is_reny == 1){
                $price += $itemInfo->original_price * $item['count'];
            }else {
                $price += $itemInfo->price * $item['count'];
            }
            $freight = $itemInfo->freight * $item['count'];
            $deliver_type =[];
            if($itemInfo->category->type == 0) {
                switch ($itemInfo->item_type) {
                    case 0:
                        $deliver_type = [
                            '0' => '配送',
                            '5' => '自取'
                        ];
                        break;
                    case 1:
                        $deliver_type = [
                            '0' => '配送',
                            '1' => '认养',
                            '2' => '存入仓库',
                            '3' => '分期配送',
                            '5' => '自取'
                        ];
                        break;
                    case 2:
                        $deliver_type = [
                            '0' => '配送',
                            '2' => '存入仓库',
                            '3' => '分期配送',
                            '5' => '自取'
                        ];
                    case 3:
                        $deliver_type = [
                            '0' => '配送',
                            '2' => '存入仓库',
                            '3' => '分期配送',
                            '5' => '自取'
                        ];
                    case 4:
                        $deliver_type = [
                            '0' => '配送',
                            '2' => '存入仓库',
                            '3' => '分期配送',
                            '5' => '自取'
                        ];
                        break;
                }
            }
            else{
                $num =0;
                switch ($itemInfo->item_type) {
                    case 0:
                        $deliver_type = [
                            '3' => '分期配送',
                        ];
                        $num=0;
                        break;
                    case 1:
                        $deliver_type = [
                            '3' => '分期配送',
                        ];
                        $num=12;
                        break;
                    case 2:
                        $deliver_type = [
                            '3' => '分期配送',
                        ];
                        $num=360;
                    case 3:
                        $deliver_type = [
                            '3' => '分期配送',
                        ];
                        $num=360;
                    case 4:
                        $deliver_type = [
                            '3' => '分期配送',
                        ];
                        $num=12;
                        break;
                }

            }
            if(isset($is_reny)&&$is_reny == 1) {
                   $item_contents[] = ['item_id' => $itemInfo->id,
                       'title' => $itemInfo->title,
                       'cover' => ['url' => Storage::url($itemInfo->covers->first()->path)],
                       'count' => $item['count'],
                       'price' => $item['count'] * $itemInfo->original_price,
                       'unit_price'=>$itemInfo->unit_price,
                       'item_type' => $itemInfo->item_type,
                       'original_price'=>$itemInfo->original_price,
                       'deliver_type' => $deliver_type,
                       'num'=>null,
                   ];
            }
            else {
                $item_contents[] = ['item_id' => $itemInfo->id,
                    'title' => $itemInfo->title,
                    'cover' => ['url' => Storage::url($itemInfo->covers->first()->path)],
                    'count' => $item['count'],
                    'price' => $item['count'] * $itemInfo->price,
                    'unit_price'=>$itemInfo->unit_price,
                    'item_type' => $itemInfo->item_type,
                    'original_price'=>$itemInfo->original_price,
                    'deliver_type' => $deliver_type,
                    'num'=>$num??null,
                ];
            }
        }
        if(isset($is_reny)) {
            $coupons = $user = auth()->user()->usecoupons->where('base_money', '<=', $price);
        }
        else{
            $coupons = $user = auth()->user()->usecoupons->where('base_money', '<=', $price)->where('type',0);
        }

        if(Freight::first()->free <= $price){
            $freight = 0;
        }
        $coupons_data = [];
        foreach ($coupons as $coupon){
            $coupons_data[] = $coupon;
        }
        $data = [
            'coupons' =>$coupons_data,
            'items'=>$item_contents,
            'freight'=>$freight,
            'price'=>$price+$freight
        ];
        return response()->json($data);
    }

    public function rgrecord(){
        $user = auth()->user();
        $orders = Order::where('status',Order::FINISH)->where('user_id',$user->id)->where('type',Order::RECHARGE_TYPE)->get();
        return api()->collection($orders,RgRecordResource::class);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
//    public function refundNotify()
//    {
//        return Payment::app()->refundNotify(function ($message, $reqInfo, $fail) {
//            if (!$reqInfo) {
//                return $fail('解密失败');
//            }
//
//            $refund = OrderRefund::filterSn($reqInfo['out_refund_no']);
//            if (!$refund || $refund->status === OrderRefund::SUCCESS || $refund->status === OrderRefund::REFUSE) {
//                return true;
//            }
//
//            ///
//            if (array_get($reqInfo, 'refund_status') === 'SUCCESS') {
//                $refund->price = $reqInfo['settlement_refund_fee'] / 100;
//                $refund->status = OrderRefund::SUCCESS;
//                $refund->save();
//            }
//            else {
//                Log::error('Refund Error:' . $reqInfo['refund_status']);
//            }
//
//            return true;
//        });
//    }

}