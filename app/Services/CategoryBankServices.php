<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Interfaces\AccountBankTypeInterfaces;
use App\Interfaces\CategoryBankInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryBankServices
{
    use ApiResponse;

    protected $categoryBankInterfaces;

    public function __construct(CategoryBankInterfaces $categoryBankInterfaces)
    {
        $this->categoryBankInterfaces = $categoryBankInterfaces;
    }
    public function getAll()
    {
        try {
            return $this->categoryBankInterfaces->getAll();
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function store(array $data)
    {
        try {
            return $this->categoryBankInterfaces->store($data);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function update(array $data, $id)
    {
        try {
            return $this->categoryBankInterfaces->update($data, $id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            return $this->categoryBankInterfaces->delete($id);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
