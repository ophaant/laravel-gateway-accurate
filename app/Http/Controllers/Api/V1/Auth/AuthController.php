<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authServices;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
        $this->middleware('permission:users-create', ['only' => ['register']]);
    }
    public function register(RegisterRequest $request)
    {
        return $this->authServices->register($request);
    }

    public function login(RegisterRequest $request)
    {
        return $this->authServices->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }
}
