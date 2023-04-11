<?php

namespace App\Services\Accurate;

use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateDatabaseInterfaces;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateSessionServices
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
    public function storeSession()
    {
        $token = $this->accurateTokenInterfaces->getAccessToken();
        $databases = $this->accurateDatabaseInterfaces->getDatabase();
        try {
            $dataResp = [];
            $databases->each(function ($database) use ($token, &$dataResp) {

                $respSession = sendReq('get', config('accurate.auth_url') . 'api/open-db.do', ['id' => $database->code_database], false, false, $token);
                if ($respSession['http_code'] != 200) {
                    Log::debug($respSession);
                    return $this->errorResponse(isset($respSession['error']) ? $respSession['error'] : (isset($respSession['message']) ? $respSession['message'] : $respSession['d'][0]),
                        $respSession['http_code'], errorCodes::ACC_SESSION_FAILED,
                        isset($respSession['error_description']) ? $respSession['error_description'] : (isset($respSession['error_detail']) ? $respSession['error_detail'] : null));
                }
                $arrayResp = [
                    'session' => $respSession['session'],
                    'database_id' => $database->id];
                $dataResp[] = $arrayResp;
            });

            return $this->accurateSessionInterfaces->storeSessionAccurate($dataResp);
        }catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
