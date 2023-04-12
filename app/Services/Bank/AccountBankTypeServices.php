<?php

namespace App\Services\Bank;

use App\Helpers\errorCodes;
use App\Interfaces\Bank\AccountBankTypeInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AccountBankTypeServices
{
    use ApiResponse;

    protected $accountBankTypeInterfaces;

    public function __construct(AccountBankTypeInterfaces $accountBankTypeInterfaces)
    {
        $this->accountBankTypeInterfaces = $accountBankTypeInterfaces;
    }
    public function getAll()
    {
        try {
            return $this->accountBankTypeInterfaces->getAll();
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
