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

class OrderItem extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['item_id', 'count', 'price','type'];

    /**
     * 商品类型0普通商品 1表示认养 2表示存入仓库 3表示分期配送 4表示充值商品 5表示自取
     */
    const NONAL_TYPE = 0;
    const RENY_TYPE = 1;
    const CANK_TYPE = 2;
    const TIME_TYPE = 3;
    const RECHARGE_TYPE = 4;
    const SELF_TYPE=5;

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