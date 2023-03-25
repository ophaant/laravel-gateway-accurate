<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Helpers\errorCodes;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Services\AccurateAuthServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function setDatabase(){
        return $this->accurateAuthServices->storeDatabases();
    }
}
