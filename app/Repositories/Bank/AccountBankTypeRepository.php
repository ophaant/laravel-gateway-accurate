<?php

namespace App\Repositories\Bank;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Bank\AccountBankTypeInterfaces;
use App\Models\AccountBankType;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log;

class AccountBankTypeRepository implements AccountBankTypeInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $accountBankType = AccountBankType::all();
            return $this->successResponse($accountBankType, 200, 'Account Bank Type Get Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}

