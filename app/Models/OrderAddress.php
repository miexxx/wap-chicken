<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/26
 * Time: 9:44
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_name', 'postal_code', 'tel', 'detail'];
}