<?php

namespace App\Services\Bank;

use App\Helpers\errorCodes;
use App\Interfaces\Bank\BankInterfaces;
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

    public function getById($id)
    {
        try {
            return $this->bankInterfaces->getById($id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->bankInterfaces->create($data);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function update(array $data, $id)
    {
        try {
            return $this->bankInterfaces->update($data, $id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            return $this->bankInterfaces->delete($id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }
}
