<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/23
 * Time: 17:13
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderStageItem extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function covers()
    {
        return $this->hasMany(ItemCover::class, 'item_id', 'item_id');
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
}