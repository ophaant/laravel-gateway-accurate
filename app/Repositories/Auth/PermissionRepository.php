<?php

namespace App\Repositories\Auth;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Auth\PermissionInterfaces;
use App\Models\Permission;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionRepository implements PermissionInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $permission = Permission::orderBy('id')->get();
            return $permission;
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
            $permission = Permission::find($id);
            if (!$permission) {
                return $this->errorResponse('Permission Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            return $permission;
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
            $permission = Permission::create($data);
            DB::commit();
            return $permission;
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
            $permission = Permission::find($id);
            if (!$permission) {
                return $this->errorResponse('Permission Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $permission->delete();
            DB::commit();
            return $this->successResponse($permission, 200, 'Permission Delete Successfully');
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
            $permission = Permission::find($id);
            if (!$permission) {
                return $this->errorResponse('Permission Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $permission->update($data);
            DB::commit();
            return $permission;
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

