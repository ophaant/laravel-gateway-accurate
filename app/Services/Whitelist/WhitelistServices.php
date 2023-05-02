<?php

namespace App\Services\Whitelist;

use App\Helpers\errorCodes;
use App\Interfaces\Bank\BankInterfaces;
use App\Interfaces\Whitelist\WhitelistInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class WhitelistServices
{
    use ApiResponse;

    protected $whiltelistInterfaces;

    public function __construct(WhitelistInterfaces $whiltelistInterfaces)
    {
        $this->whiltelistInterfaces = $whiltelistInterfaces;
    }

    public function getAll()
    {
        try {
            return $this->whiltelistInterfaces->getAll();
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function getById($id)
    {
        try {
            return $this->whiltelistInterfaces->getById($id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->whiltelistInterfaces->create($data);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function update(array $data, $id)
    {
        try {
            return $this->whiltelistInterfaces->update($data, $id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            return $this->whiltelistInterfaces->delete($id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }
}
