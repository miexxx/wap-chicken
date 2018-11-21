<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresss';
    protected $guarded=[];
    const DEFALUT_ADD = 1;
    const NORNAL_ADD=0;
}
