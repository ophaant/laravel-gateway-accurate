<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\AccurateItemServices;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $accurateItemServices;
    public function __construct(AccurateItemServices $accurateItemServices)
    {
        $this->accurateItemServices = $accurateItemServices;
    }

    public function getItem(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
        return $this->accurateItemServices->getItem($code_database, $page);
//        return $this->accurateItemServices->getAllItem($code_database);
    }
}
