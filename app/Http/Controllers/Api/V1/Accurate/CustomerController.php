<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\Accurate\AccurateCustomerServices;

class CustomerController extends Controller
{

    protected $accurateCustomerServices;
    public function __construct(AccurateCustomerServices $accurateCustomerServices)
    {
        $this->accurateCustomerServices = $accurateCustomerServices;
        $this->middleware('permission:customers-read', ['only' => ['index']]);
        $this->middleware('permission:customers-create', ['only' => ['store']]);
    }

    public function index(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
        return $this->accurateCustomerServices->getCustomer($code_database, $page);
    }
    public function store(CustomerRequest $request)
    {
        return $this->accurateCustomerServices->saveCustomer($request);
    }

}
