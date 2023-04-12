<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Interfaces\BankInterfaces;
use App\Interfaces\CategoryBankInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class BankServices
{
    use ApiResponse;

    protected $bankInterfaces;

    public function __construct(BankInterfaces $bankInterfaces)
    {
        $this->bankInterfaces = $bankInterfaces;
    }
    public function getAll()
    {
        try {
            return $this->bankInterfaces->getAll();
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
