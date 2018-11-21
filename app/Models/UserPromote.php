<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPromote extends Model
{
    protected $fillable = ['order_id','money', 'pro_money'];

    const NO_PAY = 1;
    const PAY = 2;
    const FINISH = 3;

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * 100;
    }

    public function getMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }

    public function setProMoneyAttribute($value)
    {
        $this->attributes['pro_money'] = $value * 100;
    }

    public function getProMoneyAttribute($value)
    {
        return round($value / 100 / 100, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
