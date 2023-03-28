<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\AccurateCustomerServices;

class CustomerController extends Controller
{

    protected $accurateCustomerServices;
    public function __construct(AccurateCustomerServices $accurateCustomerServices)
    {
        $this->accurateCustomerServices = $accurateCustomerServices;
    }

    public function getCustomer(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
        return $this->accurateCustomerServices->getCustomer($code_database, $page);
//        return $this->accurateCustomerServices->getAllCustomer($code_database);
    }

}
