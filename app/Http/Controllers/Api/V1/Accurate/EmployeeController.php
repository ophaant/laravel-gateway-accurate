<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\AccurateEmployeeServices;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $accurateEmployeeServices;
    public function __construct(AccurateEmployeeServices $accurateEmployeeServices)
    {
        $this->accurateEmployeeServices = $accurateEmployeeServices;
    }

    public function getEmployee(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
//        return $this->accurateEmployeeServices->getEmployee($code_database, $page);
        return $this->accurateEmployeeServices->getAllEmployee($code_database);
    }
}
