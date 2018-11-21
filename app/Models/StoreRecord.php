<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRecord extends Model
{
    protected $table = 'storerecord';
    protected $guarded = [];
    const DELIVE = 0;
    const SEND = 1;
    const BUY = 2;

    public function storeitem(){
        return $this->hasOne(Storehouse::class,'id','store_id')->withTrashed();
    }
    public function toUser(){
        return $this->hasOne(User::class,'id','touser');
    }
}
