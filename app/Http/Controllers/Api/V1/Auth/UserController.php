<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function index()
    {
        return $this->userServices->getAll();
    }

    public function show($id)
    {
        return $this->userServices->getById($id);
    }

    public function update($id, RegisterRequest $request)
    {
        return $this->userServices->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->userServices->delete($id);
    }
}

