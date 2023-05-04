<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\Auth\RoleServices;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleServices;

    public function __construct(RoleServices $roleServices)
    {
        $this->roleServices = $roleServices;
    }

    public function index()
    {
        return $this->roleServices->getAll();
    }

    public function store(RoleRequest $request)
    {
        return $this->roleServices->create($request->all());
    }

    public function show($id)
    {
        return $this->roleServices->getById($id);
    }

    public function update($id, RoleRequest $request)
    {
        return $this->roleServices->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->roleServices->delete($id);
    }
}
