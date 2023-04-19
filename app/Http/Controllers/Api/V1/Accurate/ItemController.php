<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Services\Accurate\AccurateItemServices;

class ItemController extends Controller
{
    protected $accurateItemServices;
    public function __construct(AccurateItemServices $accurateItemServices)
    {
        $this->accurateItemServices = $accurateItemServices;
        $this->middleware('permission:items-read', ['only' => ['index']]);
    }

    public function index(CustomerRequest $request)
    {
        $code_database = $request->code_database;
        $page = $request->page;
        return $this->accurateItemServices->getItem($code_database, $page);
    }
}
