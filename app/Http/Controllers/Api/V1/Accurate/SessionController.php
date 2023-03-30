<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Interfaces\AccurateSessionInterfaces;
use App\Services\AccurateDatabaseServices;
use App\Services\AccurateSessionServices;

class SessionController extends Controller
{
    protected $accurateSessionServices;
    protected $accurateSessionInterfaces;
    public function __construct(AccurateSessionServices $accurateSessionServices,AccurateSessionInterfaces $accurateSessionInterfaces)
    {
        $this->accurateSessionServices = $accurateSessionServices;
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
    }
    public function index(){
        return $this->accurateSessionInterfaces->getSessionAccurate(237751);
    }

    public function session(){
        return $this->accurateSessionServices->storeSession();
    }
}
