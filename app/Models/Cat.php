<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tanmo\Search\Traits\Search;

class Cat extends Model
{
    use Search;
    protected $table='cats';
    protected $guarded=[];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(){
        return $this->hasOne(Item::class,'id','item_id')->withTrashed();
    }

    public function covers()
    {
        return $this->hasMany(ItemCover::class,'item_id','item_id');
    }
}
