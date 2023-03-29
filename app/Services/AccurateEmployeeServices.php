<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Repositories\AccurateEmployeeRepository;
use App\Repositories\AccurateSessionRepository;
use App\Repositories\AccurateTokenRepository;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateEmployeeServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionRepository;
    protected $accurateTokenRepository;
    protected $accurateEmployeeRepository;

    public function __construct(AccurateSessionRepository $accurateSessionRepository, AccurateTokenRepository $accurateTokenRepository, AccurateEmployeeRepository $accurateEmployeeRepository)
    {
        $this->accurateSessionRepository = $accurateSessionRepository;
        $this->accurateTokenRepository = $accurateTokenRepository;
        $this->accurateEmployeeRepository = $accurateEmployeeRepository;
    }

    public function getEmployee($code_database, $page = 1)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
        $token = $this->accurateTokenRepository->getAccessToken();
        try {

            $params = [
                'fields' => 'id,name,number',
                'sp.sort' => 'id|desc',
                'sp.pageSize' => 100,
                'sp.page' => $page
            ];
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respEmployee = sendReq('GET', $url . 'employee/list.do', $params, false, false, null, $headers);

            if ($respEmployee['http_code'] != 200) {
                Log::debug($respEmployee);
                return $this->errorResponse(isset($respEmployee['error']) ? $respEmployee['error'] : (isset($respEmployee['message']) ? $respEmployee['message'] : $respEmployee['d'][0]),
                    $respEmployee['http_code'], errorCodes::ACC_EMP_FAILED,
                    isset($respEmployee['error_description']) ? $respEmployee['error_description'] : (isset($respEmployee['error_detail']) ? $respEmployee['error_detail'] : null));
            }
            $data = $respEmployee['d'];
            $meta = [
                'pageCount' => $respEmployee['sp']['pageCount'],
                'page' => $respEmployee['sp']['page'],
                'pageSize' => $respEmployee['sp']['pageSize'],
                'totalPage' => $respEmployee['sp']['rowCount'],
            ];
            return $this->successResponse($data, 200, 'Employees Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function getAllEmployee($code_database)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
        $token = $this->accurateTokenRepository->getAccessToken();
        try {

            $page = 1;
            $results = [];
            do{
                $params = [
                    'fields' => 'id,name,number',
                    'sp.sort' => 'id|desc',
                    'sp.pageSize' => 100,
                    'sp.page' => $page
                ];
                $headers = [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'X-Session-ID' => $session,
                ];

                $respEmployee = sendReq('GET', $url . 'employee/list.do', $params, false, false, null, $headers);

                if ($respEmployee['http_code'] != 200) {
                    Log::debug($respEmployee);
                    return $this->errorResponse(isset($respEmployee['error']) ? $respEmployee['error'] : (isset($respEmployee['message']) ? $respEmployee['message'] : $respEmployee['d'][0]),
                        $respEmployee['http_code'], errorCodes::ACC_EMP_FAILED,
                        isset($respEmployee['error_description']) ? $respEmployee['error_description'] : (isset($respEmployee['error_detail']) ? $respEmployee['error_detail'] : null));
                }

                $data = $respEmployee['d'];
                $meta = [
                    'pageCount' => $respEmployee['sp']['pageCount'],
                    'page' => $respEmployee['sp']['page'],
                    'pageSize' => $respEmployee['sp']['pageSize'],
                    'totalPage' => $respEmployee['sp']['rowCount'],
                ];
                $results = array_merge($results, $data);

                $page++;
            }while($page <= $meta['pageCount']);

            return $this->accurateEmployeeRepository->storeEmployee($results, $code_database);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
