<?php

namespace App\Repositories\Auth;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Auth\AuthInterfaces;
use App\Models\JournalVoucherUpload;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthRepository implements AuthInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $user = User::select('id','name','email')->orderByDesc('updated_at')->simplePaginate(10);
            return $user;
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
            $user = User::find($id);
            if (!$user) {
                return $this->errorResponse('User Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            return $user;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $user = User::create($data);
            DB::commit();
            return $user;
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
            $user = User::find($id);
            if (!$user) {
                return $this->errorResponse('User Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $user->delete();
            DB::commit();
            return $this->successResponse($user, 200, 'User Delete Successfully');
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
            $user = User::find($id);
            if (!$user) {
                return $this->errorResponse('User Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $user->update($data);
            DB::commit();
            return $user;
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

