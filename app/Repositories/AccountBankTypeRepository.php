<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccountBankTypeInterfaces;
use App\Interfaces\Accurate\AccurateCustomerInterfaces;
use App\Models\AccountBankType;
use App\Models\Customer;
use App\Models\Database;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

