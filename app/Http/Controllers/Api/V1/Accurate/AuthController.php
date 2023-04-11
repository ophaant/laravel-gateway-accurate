<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Services\Accurate\AccurateAuthServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $accurateAuthServices;
    public function __construct(AccurateAuthServices $accurateAuthServices)
    {
        $this->accurateAuthServices = $accurateAuthServices;
    }

    public function getCode(){
        return $this->accurateAuthServices->getCode();
    }

    public function oauthCallback(Request $request){
        return $this->accurateAuthServices->oauthCallback($request);
    }

    public function refreshToken(){
        return $this->accurateAuthServices->refreshToken();
    }
}
