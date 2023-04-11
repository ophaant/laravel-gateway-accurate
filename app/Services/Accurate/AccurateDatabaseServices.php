<?php

namespace App\Services\Accurate;

use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateDatabaseInterfaces;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateDatabaseServices
{
    use ApiResponse;

    protected $accurateTokenInterfaces;
    protected $accurateDatabaseInterfaces;
    protected $accurateSessionInterfaces;

    public function __construct(AccurateTokenInterfaces $accurateAuthInterfaces, AccurateDatabaseInterfaces $accurateDatabaseInterfaces, AccurateSessionInterfaces $accurateSessionInterfaces)
    {
        $this->accurateTokenInterfaces = $accurateAuthInterfaces;
        $this->accurateDatabaseInterfaces = $accurateDatabaseInterfaces;
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
    }
    public function storeDatabase()
    {
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {
            $respDatabases = sendReq('get', config('accurate.auth_url') . 'api/db-list.do', [], false, false, $token);

            if ($respDatabases['http_code'] != 200) {
                Log::debug($respDatabases);
                return $this->errorResponse(isset($respDatabases['error']) ? $respDatabases['error'] : (isset($respDatabases['message']) ? $respDatabases['message'] : $respDatabases['d'][0]),
                    $respDatabases['http_code'], errorCodes::ACC_DB_FAILED,
                    isset($respDatabases['error_description']) ? $respDatabases['error_description'] : (isset($respDatabases['error_detail']) ? $respDatabases['error_detail'] : null));
            }

            return $this->accurateDatabaseInterfaces->storeDatabase($respDatabases['d']);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }
    public function getDatabase()
    {
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {
            $respDatabases = sendReq('get', config('accurate.auth_url') . 'api/db-list.do', [], false, false, $token);

            if ($respDatabases['http_code'] != 200) {
                Log::debug($respDatabases);
                return $this->errorResponse(isset($respDatabases['error']) ? $respDatabases['error'] : (isset($respDatabases['message']) ? $respDatabases['message'] : $respDatabases['d'][0]),
                    $respDatabases['http_code'], errorCodes::ACC_DB_FAILED,
                    isset($respDatabases['error_description']) ? $respDatabases['error_description'] : (isset($respDatabases['error_detail']) ? $respDatabases['error_detail'] : null));
            }

            return $this->successResponse($respDatabases['d'], 200, 'List Databases Successfully');
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }
    }
}
