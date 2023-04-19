<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Services\Accurate\AccurateSessionServices;

class SessionController extends Controller
{
    protected $accurateSessionServices;
    protected $accurateSessionInterfaces;
    public function __construct(AccurateSessionServices $accurateSessionServices,AccurateSessionInterfaces $accurateSessionInterfaces)
    {
        $this->accurateSessionServices = $accurateSessionServices;
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
        $this->middleware('permission:sessions-create', ['only' => ['store']]);
    }
    public function store(){
        return $this->accurateSessionServices->storeSession();
    }
}
