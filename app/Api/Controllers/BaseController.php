<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\Introduction;
use App\Models\Operation;
use App\Models\Distribution;
use App\Models\AfterSale;
use App\Models\Customer;

class BaseController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function introduction()
    {
        $introduction = Introduction::first();

        return api()->item($introduction, BaseResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function operation()
    {
        $operation = Operation::first();

        return api()->item($operation, BaseResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function distribution()
    {
        $distribution = Distribution::first();

        return api()->item($distribution, BaseResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function afterSale()
    {
        $afterSale = AfterSale::first();

        return api()->item($afterSale, BaseResource::class);
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function customer()
    {
        $customer = Customer::first();

        return api()->item($customer, BaseResource::class);
    }
}
