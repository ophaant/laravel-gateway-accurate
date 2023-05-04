<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\RoleRequest;
use App\Services\Auth\PermissionServices;
use App\Services\Auth\RoleServices;

class PermissionController extends Controller
{
    protected $permissionServices;

    public function __construct(PermissionServices $permissionServices)
    {
        $this->permissionServices = $permissionServices;
    }

    public function index()
    {
        return $this->permissionServices->getAll();
    }

    public function store(PermissionRequest $request)
    {
        return $this->permissionServices->create($request->all());
    }

    public function show($id)
    {
        return $this->permissionServices->getById($id);
    }

    public function update($id, PermissionRequest $request)
    {
        return $this->permissionServices->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->permissionServices->delete($id);
    }
}
