<?php

namespace App\Repositories\JournalVoucherUpload;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\Bank\BankInterfaces;
use App\Interfaces\JournalVoucherUpload\JournalVoucherUploadInterfaces;
use App\Models\Bank;
use App\Models\JournalVoucherUpload;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalVoucherUploadRepository implements JournalVoucherUploadInterfaces
{
    use ApiResponse, HasUuids;
    public function getAll()
    {
        try {
            $bank = JournalVoucherUpload::with('bank:id,account_name','database:id,code_database,name')->orderByDesc('updated_at')->paginate(1);
            return $bank;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function getById($id)
    {
        try {
            $bank = JournalVoucherUpload::find($id);
            if (!$bank) {
                return $this->errorResponse('Journal Voucher Upload Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            return $bank;
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $bank = JournalVoucherUpload::create($data);
            DB::commit();
            return $this->successResponse($bank, 200, 'Journal Voucher Upload Successfully');
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
            $bank = JournalVoucherUpload::find($id);
            if (!$bank) {
                return $this->errorResponse('Journal Voucher Upload Not Found', 404, errorCodes::CODE_WRONG_ERROR);
            }
            $bank->delete();
            DB::commit();
            return $this->successResponse($bank, 200, 'Journal Voucher Upload Delete Successfully');
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

