<?php

namespace App\Services\Auth;

use App\Exceptions\handleDatabaseException;
use App\Exports\JournalVoucherUploadExport;
use App\Helpers\errorCodes;
use App\Imports\JournalVoucherUploadImport;
use App\Interfaces\Auth\AuthInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDOException;

class AuthServices
{
    use ApiResponse;

    protected $authInterfaces;

    public function __construct(AuthInterfaces $authInterfaces)
    {
        $this->authInterfaces = $authInterfaces;
    }

    public function register($request)
    {
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = $this->authInterfaces->create($input);
            $data['token'] =  $user->createToken('MyApp')->accessToken;
            $data['name'] =  $user->name;
            return $this->successResponse($data, 200, 'User Register Successfully');
        }catch (\PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function getAll()
    {
        try {
            $journalVoucherUpload = $this->journalVoucherUploadInterfaces->getAll();
            return $this->successResponse($journalVoucherUpload, 200, 'Journal Voucher Upload List Get Successfully');
        } catch (PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function delete($id)
    {
        try {
            $journalVoucherUpload = $this->journalVoucherUploadInterfaces->getById($id);
            $this->journalVoucherUploadInterfaces->delete($id);
            return $this->successResponse($journalVoucherUpload, 200, 'Journal Voucher Upload Delete Successfully');
        } catch (PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
}
