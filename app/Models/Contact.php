<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Contact extends Model
{
    /**
     * @param $value
     * @return string
     */
    public function getCodeImgAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }
}
