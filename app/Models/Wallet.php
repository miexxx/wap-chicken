<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $guarded=[];
    const WAIT=1;
    const NORNAL=0;

    public function getMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }
    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * 100;
    }



    public function getCanMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }
    public function setCanMoneyAttribute($value)
    {
        $this->attributes['can_money'] = $value * 100;
    }
}
