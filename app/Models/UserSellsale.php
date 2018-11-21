<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSellsale extends Model
{
    protected $table = 'user_sellsale';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function support(){
        return $this->hasOne(Support::class,'id','support_id');
    }
}
