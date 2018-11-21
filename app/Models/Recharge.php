<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $table = 'recharges';
    protected $guarded=[];

    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }

    public function setFreeAttribute($value)
    {
        $this->attributes['free'] = $value * 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getFreeAttribute($value)
    {
        return round($value / 100, 2);
    }
}
