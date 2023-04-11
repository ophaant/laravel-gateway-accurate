<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccountBankTypeInterfaces;
use App\Interfaces\CategoryBankInterfaces;
use App\Models\AccountBankType;
use App\Models\CategoryBank;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log;

class CategoryBankRepository implements CategoryBankInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $accountBankType = CategoryBank::all();
            return $this->successResponse($accountBankType, 200, 'Category Bank Get Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}

