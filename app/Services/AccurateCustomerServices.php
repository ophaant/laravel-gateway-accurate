<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Interfaces\AccurateCustomerInterfaces;
use App\Interfaces\AccurateSessionInterfaces;
use App\Interfaces\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateCustomerServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionInterfaces;
    protected $accurateTokenInterfaces;
    protected $accurateCustomerInterfaces;

    public function __construct(AccurateSessionInterfaces $accurateSessionInterfaces, AccurateTokenInterfaces $accurateTokenInterfaces, AccurateCustomerInterfaces $accurateCustomerInterfaces)
    {
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
        $this->accurateTokenInterfaces = $accurateTokenInterfaces;
        $this->accurateCustomerInterfaces = $accurateCustomerInterfaces;
    }

    public function getCustomer($code_database, $page = 1)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionInterfaces->getSessionAccurate($code_database);
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {
            $params = [
                'fields' => 'customerNo,name,id',
                'sp.sort' => 'id|desc',
                'sp.pageSize' => 100,
                'sp.page' => $page
            ];
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respCustomer = sendReq('GET', $url . 'customer/list.do', $params, false, false, null, $headers);

            if ($respCustomer['http_code'] != 200) {
                Log::debug($respCustomer);
                return $this->errorResponse(isset($respCustomer['error']) ? $respCustomer['error'] : (isset($respCustomer['message']) ? $respCustomer['message'] : $respCustomer['d'][0]),
                    $respCustomer['http_code'], errorCodes::ACC_CUST_FAILED,
                    isset($respCustomer['error_description']) ? $respCustomer['error_description'] : (isset($respCustomer['error_detail']) ? $respCustomer['error_detail'] : null));
            }

            $data = $respCustomer['d'];
            $meta = [
                'pageCount' => $respCustomer['sp']['pageCount'],
                'page' => $respCustomer['sp']['page'],
                'pageSize' => $respCustomer['sp']['pageSize'],
                'totalPage' => $respCustomer['sp']['rowCount'],
            ];
            return $this->successResponse($data, 200, 'Customers Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function getAllCustomer($code_database)
    {
        try {
            $url = $this->checkDatabaseAccurate($code_database);
            $session = $this->accurateSessionInterfaces->getSessionAccurate($code_database);
            $token = $this->accurateTokenInterfaces->getAccessToken();

            $page = 1;
            $results = [];
            do {
                $params = [
                    'fields' => 'customerNo,name,id',
                    'sp.sort' => 'id|desc',
                    'sp.pageSize' => 100,
                    'sp.page' => $page
                ];
                $headers = [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'X-Session-ID' => $session,
                ];

                $respCustomer = sendReq('GET', $url . 'customer/list.do', $params, false, false, null, $headers);

                if ($respCustomer['http_code'] != 200) {
                    Log::debug($respCustomer);
                    return $this->errorResponse(isset($respCustomer['error']) ? $respCustomer['error'] : (isset($respCustomer['message']) ? $respCustomer['message'] : $respCustomer['d'][0]),
                        $respCustomer['http_code'], errorCodes::ACC_CUST_FAILED,
                        isset($respCustomer['error_description']) ? $respCustomer['error_description'] : (isset($respCustomer['error_detail']) ? $respCustomer['error_detail'] : null));
                }

                $data = $respCustomer['d'];
                $meta = [
                    'pageCount' => $respCustomer['sp']['pageCount'],
                    'page' => $respCustomer['sp']['page'],
                    'pageSize' => $respCustomer['sp']['pageSize'],
                    'totalPage' => $respCustomer['sp']['rowCount'],
                ];
                $results = array_merge($results, $data);

                $page++;
            } while ($page <= $meta['pageCount']);

            return $this->accurateCustomerInterfaces->storeCustomer($results, $code_database);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function saveCustomer($request)
    {
        try {
            $url = $this->checkDatabaseAccurate($request->code_database);
            $session = $this->accurateSessionInterfaces->getSessionAccurate($request->code_database);
            $token = $this->accurateTokenInterfaces->getAccessToken();

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respCustomer = sendReq('POST', $url . 'customer/save.do', $request, false, false, null, $headers);

            if (!$respCustomer['s']) {
                Log::debug($respCustomer);
                return $this->errorResponse(isset($respCustomer['error']) ? $respCustomer['error'] : (isset($respCustomer['message']) ? $respCustomer['message'] : $respCustomer['d'][0]),
                    400, errorCodes::ACC_CUST_FAILED,
                    isset($respCustomer['error_description']) ? $respCustomer['error_description'] : (isset($respCustomer['error_detail']) ? $respCustomer['error_detail'] : null));
            }

            $data = $respCustomer['d'];

            return $this->successResponse($data, 200, 'Customers Successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }
}
