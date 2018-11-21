<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserCode extends Model
{
    protected $fillable = ['code','code_url'];

    public function user(){
        return $this->belongsTo(User::class,'create_uid','id');
    }

    public function getCodeUrlAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }
}
