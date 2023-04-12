<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\BankInterfaces;
use App\Interfaces\CategoryBankInterfaces;
use App\Models\Bank;
use App\Models\CategoryBank;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
}

