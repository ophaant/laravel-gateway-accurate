<?php

namespace App\Repositories\Auth;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Auth\RoleInterfaces;
use App\Models\Role;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleRepository implements RoleInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $role = Role::all();
            return $role;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function getById($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return $this->errorResponse('Role Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
//            dd($role->permissions()->get());
            $role['permissions'] = $role->permissions()->get(['id','name']);
            return $role;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $role = Role::create($data);
            $role->givePermissions(explode(',', $data['permissions']));
            DB::commit();
            return $role;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $role = Role::find($id);
            if (!$role) {
                return $this->errorResponse('Role Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $role->delete();
            DB::commit();
            return $this->successResponse($role, 200, 'Role Delete Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function update($id,$data)
    {
        try {
            DB::beginTransaction();
            $role = Role::find($id);
            if (!$role) {
                return $this->errorResponse('Role Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $role->update($data);
            DB::commit();
            return $role;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}

