<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletApply extends Model
{
    protected $table = 'wallets_apply';
    protected $guarded=[];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }
    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * 100;
    }
}
