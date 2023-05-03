<?php

namespace App\Repositories\Whitelist;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Whitelist\WhitelistInterfaces;
use App\Models\BlockIp;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhitelistRepository implements WhitelistInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $whitelist = BlockIp::get();
            return $this->successResponse($whitelist, 200, 'BlockIp List Get Successfully');
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
            $whitelist = BlockIp::find($id);
            return $this->successResponse($whitelist, 200, 'BlockIp Get Successfully');
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
            $whitelist = BlockIp::create($data);
            DB::commit();
            return $this->successResponse($whitelist, 200, 'BlockIp Create Successfully');
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

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $whitelist = BlockIp::find($id);
            if (!$whitelist) {
                return $this->errorResponse('BlockIp Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $whitelist->update($data);
            DB::commit();
            return $this->successResponse($whitelist, 200, 'BlockIp Update Successfully');
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
            $whitelist = BlockIp::find($id);
            if (!$whitelist) {
                return $this->errorResponse('BlockIp Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $whitelist->delete();
            DB::commit();
            return $this->successResponse($whitelist, 200, 'BlockIp Delete Successfully');
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

    public function getByStatusEnable($ip)
    {
        try {
            $whitelist = BlockIp::where('ip', $ip)->where('status','Enable')->pluck('ip')->toArray();
            return $whitelist;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function getByIp($ip)
    {
        try {
            $whitelist = BlockIp::where('ip', $ip)->value('user_id');
            return $whitelist;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}

