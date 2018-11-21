<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 16:16
 * Function:
 */

namespace App\Models;


use App\Exceptions\LowStocksException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Tanmo\Search\Traits\Search;
use App\Models\UserPromote;
use App\Models\UserInvite;
use App\Models\UserLevel;
use Carbon\Carbon;

class Order extends Model
{
    use Search, SoftDeletes;

    /**
     * 订单状态:0待付款 1待发货 2待收货 3待评价 4已完成
     */
    const WAIT_PAY = 0;
    const WAIT_DELIVER = 1;
    const WAIT_RECV = 2;
    const WAIT_COMMENT = 3;
    const FINISH = 4;
    const REFUND = 5;

    /**
     * 订单类型0不存在分期配送 3表示存在分期配送 4表示充值订单
     */
    const NONAL_TYPE = 0;
    const TIME_TYPE = 1;
    const RECHARGE_TYPE = 4;

    /**
     * status map
     *
     * @var array
     */
    protected $statusMap = [
        self::WAIT_PAY => 'wait_pay',
        self::WAIT_DELIVER => 'wait_deliver',
        self::WAIT_RECV => 'wait_recv',
        self::WAIT_COMMENT => 'wait_comment',
        self::FINISH => 'finish',
        self::REFUND => 'refund'
    ];

    /**
     * @var array
     */
    protected $guarded=[];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stageItems()
    {
        return $this->hasMany(OrderStageItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(OrderAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wechatPayment()
    {
        return $this->hasOne(OrderWechatPayment::class);
    }

    /**
     * @param $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getPriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setPayablePriceAttribute($value)
    {
        $this->attributes['payable_price'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getPayablePriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param $value
     */
    public function setFreightAttribute($value)
    {
        $this->attributes['freight'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getFreightAttribute($value)
    {
        return round($value / 100, 2);
    }

    /**
     * @return mixed
     */
    public function getStatusTextAttribute()
    {
        if (empty($this->status)) {
            return $this->statusMap[self::WAIT_PAY];
        }

        return $this->statusMap[$this->status];
    }

    /**
     * @return string
     */
    public function getExpressAttribute()
    {
        $info = $this->getTrackingInfo();

        if (!empty($info['list'])) {
            return $info['list'][0]['content'];
        }

        return '暂无物流信息';
    }

    /**
     * @param Builder $builder
     * @param $userId
     * @return Builder
     */
    public function scopeFilterUserId(Builder $builder, $userId)
    {
        return $builder->where('user_id', $userId);
    }

    /**
     * @param Builder $builder
     * @param $status
     * @return Builder
     */
    public function scopeFilterStatus(Builder $builder, $status)
    {
        if (array_key_exists($status, $this->statusMap)) {
            return $builder->where('status', $status)->where('refund', 0);
        }

        $value = array_flip($this->statusMap)[$status];
        return $builder->where('status', $value)->where('refund', 0);
    }

    /**
     * @param Builder $builder
     * @param int $refund
     * @return Builder
     */
    public function scopeFilterRefund(Builder $builder, $refund = 1)
    {
        return $builder->where('refund', $refund);
    }

    /**
     * @param Builder $builder
     * @param $sn
     * @return Builder
     */
    public function scopeFilterSn(Builder $builder, $sn)
    {
        return $builder->where('sn', $sn);
    }

    //-----------------------------------------------------------------------//

    /**
     * @param User $user
     * @param array $items
     * @param array $address
     * @param null $remark
     * @return mixed
     */
    public function submit(User $user, array $items, array $address=null, $remark = null, $type ,$coupon_id=null,$is_reny=null,$reny_id=null)
    {
        return DB::transaction(function () use ($user, $items, $address, $remark, $type,$coupon_id,$is_reny,$reny_id) {
            $order = $this->create([
                'user_id' => $user->id,
                'sn' => date('YmdHis') . $user->id . rand(10, 99),
                'price' => 0,
                'payable_price' => 0,
                'items_price' => 0,
                'freight' => 0,
                'remark' => $remark,
                'type'=>$type,
                'coupon_id'=>$coupon_id,
                'reny_id'=>$reny_id,
            ]);
            if($address) {
                $orderAddress = new OrderAddress($address);
                $order->address()->save($orderAddress);
            }
            $money = 0;
            foreach ($items as $item) {
                $itemInfo = Item::where('id', $item['id'])->lockForUpdate()->first();
                if ($itemInfo && ($itemInfo->stock >= $item['count'])) {
                    /// 减库存
                    $itemInfo->stock -= $item['count'];
                    $itemInfo->sales_volume += $item['count'];
                    $itemInfo->save();

                    /// 写入数据库
                    if($item['type'] === Item::TIME_TYPE){
                        $orderStageItem = new OrderStageItem(['item_id' => $item['id'], 'count' => $item['count'], 'price' => $itemInfo->price, 'type' => $item['stage'],'num' =>$item['num'],'time'=>$item['time'],'first_time'=>$item['first_time'],'timeset'=>$item['timeset']]);
                        $order->stageItems()->save($orderStageItem);
                    }
                    else {
                        $orderItem = new OrderItem(['item_id' => $item['id'], 'count' => $item['count'], 'price' => $itemInfo->price, 'type' => $item['type']]);
                        $order->items()->save($orderItem);
                    }

                    $freight = $itemInfo->freight * $item['count'];
                    if(isset($is_reny)&&$is_reny == 1) {
                        $price = $itemInfo->original_price;
                    }
                    else {
                        $price = $itemInfo->price;
                    }

                    if($itemInfo->is_extension) {
                        $money += $price * $item['count'];
                    }

                    ///
                    $order->price += $price * $item['count'] + $freight;
                    $order->payable_price += $price * $item['count'] + $freight;
                    $order->items_price += $price * $item['count'];
                    $order->freight += $freight;
                    $user->cats()->where('item_id',$itemInfo->id)->delete();

                    if(isset($is_reny)&&$is_reny == 2){
                        //todo
                        $support = $user->supports->where('id',$reny_id)->first();
                        $support->status = Support::DIE;
                        $support->save();
                        $order->status = Order::WAIT_DELIVER;

                        $itemInfo->stock += $item['count'];
                        $itemInfo->sales_volume -= $item['count'];
                        break;

                    }

                }
                else {
                    throw new LowStocksException($item['id']);
                }
            }
            if($money>0) {
                $user->load(['parent','parent.parent']);
                if($user->is_shared) {
                    $userLevel = UserLevel::where('level',$user->is_shared)->sharedLock()->first();
                    $pro_money = $money * $userLevel->sales_percent;
                    $userPromote = new UserPromote(['order_id' => $order->id,'money' => $money,'pro_money' => $pro_money]);
                    $user->userPromote()->save($userPromote);
                }
                if($user->parent_id && $user->parent->is_shared) {
                    $userLevel = UserLevel::where('level',$user->parent->is_shared)->sharedLock()->first();
                    if($user->is_shared) {
                        $pro_money = $money *  $userLevel->sales_percent;
                        $userInvite = new UserInvite(['order_id' => $order->id,'money' => $money,'pro_money' => $pro_money]);
                        $user->parent->userInvite()->save($userInvite);
                    }
                    else{
                        $pro_money = $money *  $userLevel->sales_percent;
                        $userPromote = new UserPromote(['order_id' => $order->id,'money' => $money,'pro_money' => $pro_money]);
                        $user->parent->userPromote()->save($userPromote);
                    }
                    if($user->parent->parent_id && $user->parent->parent->is_shared) {
                        $userLevel = UserLevel::where('level',$user->parent->parent->is_shared)->sharedLock()->first();
                        $pro_money = $money * $userLevel->sales_percent;
                        $userInvite = new UserInvite(['order_id' => $order->id,'money' => $money, 'pro_money' => $pro_money]);
                        $user->parent->parent->userInvite()->save($userInvite);
                    }
                }
            }

            $order->save();

            return $order;
        });
    }

    public function submitRecharge(User $user,Recharge $recharge){
        return DB::transaction(function () use ($user,$recharge) {
            $order = $this->create([
                'user_id' => $user->id,
                'sn' => date('YmdHis') . $user->id . rand(10, 99),
                'price' => 0,
                'payable_price' => 0,
                'items_price' => 0,
                'freight' => 0,
                'type' => Order::RECHARGE_TYPE
            ]);


            $order->price += $recharge->money;
            $order->payable_price += $recharge->money;
            $order->items_price += $recharge->money+$recharge->free;

            $order->save();

            return $order;
        });

    }

    public function submitStoreHouse(User $user,Item $item,$count,array $address=null, $remark = null,$type){
        return DB::transaction(function() use($user,$item,$count,$address,$remark,$type){
            $order = $this->create([
                'user_id' => $user->id,
                'sn' => date('YmdHis') . $user->id . rand(10, 99),
                'price' => 0,
                'payable_price' => 0,
                'items_price' => 0,
                'freight' => 0,
                'remark' => $remark,
                'type' => Order::NONAL_TYPE,
            ]);
            if($address) {
                $orderAddress = new OrderAddress($address);
                $order->address()->save($orderAddress);
            }
            $orderItem = new OrderItem(['item_id' =>$item->id, 'count' => $count, 'price' => $item->price, 'type' => $type]);
            $order->items()->save($orderItem);

            $order->price += $item->price * $count ;
            $order->payable_price += $item->price * $count ;
            $order->items_price += $item->price * $count ;

            $order->status = Order::WAIT_DELIVER;
            $order->save();

            return $order;
        });
    }



    public function coupon(){
        return $this->hasOne(Coupon::class,'id','coupon_id');
    }

    /**
     * @return mixed
     */
    public function getTrackingInfo()
    {
        $query['no'] = $this->tracking_no;
        if (!empty($this->express_type) && is_string($this->express_type)) {
            $query['type'] = $this->express_type;
        }

        $data = Curl::to(config('express.api'))
            ->withHeader("Authorization:APPCODE " . config('express.app_code'))
            ->withData($query)
            ->get();

        return json_decode($data, true);
    }

    public function userPromote() {
        return $this->hasMany(UserPromote::class,'order_id','id');
    }

    public function userInvite() {
        return $this->hasMany(UserInvite::class,'order_id','id');
    }

    public function  confirmOrder($orderId)
    {
        $order = $this->find($orderId);
        $order->status = Order::FINISH;
        $order->confirmed_at = Carbon::now();
        $order->save();

        return true;
    }
    
}