<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptRule extends Model
{
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

}
