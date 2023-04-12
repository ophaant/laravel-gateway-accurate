<?php

namespace App\Repositories\Bank;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Bank\CategoryBankInterfaces;
use App\Models\CategoryBank;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryBankRepository implements CategoryBankInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $categoryBank = CategoryBank::all();
            return $this->successResponse($categoryBank, 200, 'Category Bank Get Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
//            array_push($data, ['id' => $this->newUniqueId()]);
            $categoryBank = CategoryBank::create($data);
            DB::commit();
            return $this->successResponse($categoryBank, 200, 'Category Bank Save Successfully');
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

    public function findById($id)
    {
        try {
            $categoryBank = CategoryBank::find($id);
            if (!$categoryBank){
                return $this->errorResponse('Category Bank Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            return $this->successResponse($categoryBank, 200, 'Category Bank Get Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $categoryBank = CategoryBank::find($id);
            if (!$categoryBank){
                return $this->errorResponse('Category Bank Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $categoryBank->update($data);
            DB::commit();
            return $this->successResponse($categoryBank, 200, 'Category Bank Update Successfully');
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
            $categoryBank = CategoryBank::find($id);
            if (!$categoryBank){
                return $this->errorResponse('Category Bank Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $categoryBank->delete();
            DB::commit();
            return $this->successResponse($categoryBank, 200, 'Category Bank Delete Successfully');
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

