<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\Accurate\AccurateEmployeeServices;

class EmployeeController extends Controller
{
    protected $accurateEmployeeServices;
    public function __construct(AccurateEmployeeServices $accurateEmployeeServices)
    {
        $this->accurateEmployeeServices = $accurateEmployeeServices;
        $this->middleware('permission:employees-read', ['only' => ['index']]);
    }

    public function index(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
        return $this->accurateEmployeeServices->getEmployee($code_database, $page);
    }
}
