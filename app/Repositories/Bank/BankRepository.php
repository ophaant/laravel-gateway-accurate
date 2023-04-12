<?php

namespace App\Repositories\Bank;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Bank\BankInterfaces;
use App\Models\Bank;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankRepository implements BankInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $bank = Bank::with('category:id,category_bank_name','accountType:id,account_type_name')->get();
            return $this->successResponse($bank, 200, 'Bank List Get Successfully');
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
            $bank = Bank::with('category:id,category_bank_name','accountType:id,account_type_name')->find($id);
            return $this->successResponse($bank, 200, 'Bank Get Successfully');
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
            $bank = Bank::create($data);
            DB::commit();
            return $this->successResponse($bank, 200, 'Bank Create Successfully');
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
            $bank = Bank::find($id);
            if (!$bank) {
                return $this->errorResponse('Bank Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $bank->update($data);
            DB::commit();
            return $this->successResponse($bank, 200, 'Bank Update Successfully');
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
            $bank = Bank::find($id);
            if (!$bank) {
                return $this->errorResponse('Bank Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $bank->delete();
            DB::commit();
            return $this->successResponse($bank, 200, 'Bank Delete Successfully');
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

