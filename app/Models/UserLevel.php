<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    /**
     * @param $value
     */
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
}
