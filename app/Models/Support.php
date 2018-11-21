<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'support';
    protected $guarded=[];
    const DIE = 1;
    public function item(){
        return $this->hasOne(Item::class,'id','item_id')->withTrashed();
    }

}
