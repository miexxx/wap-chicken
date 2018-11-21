<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 10:15
 * Function:
 */

namespace App\Contracts;


use Illuminate\Http\Response;

interface JwtAuthContract
{
    public function login() : Response;

    public function refresh() : Response;

    public function logout() : Response;
}