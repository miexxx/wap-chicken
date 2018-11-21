<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Storehouse extends Model
{
    use  SoftDeletes;
    protected $table = 'storehouse';
    protected $dates = ['deleted_at'];
    protected $guarded= [];
    const EGG = 0;
    const CHINCKEN=1;

    public function item(){
        return $this->hasOne(Item::class,'id','item_id')->withTrashed();
    }
}
