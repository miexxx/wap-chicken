<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Qrcodes extends Model
{
    protected $table = 'qrcodes';
    protected $guarded=[];
    const FIVE = 500;
    const ONE = 100;
    const TIME = 10;

    public function getQrcodeAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

}
