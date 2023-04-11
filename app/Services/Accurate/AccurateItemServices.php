<?php

namespace App\Services\Accurate;

use App\Helpers\errorCodes;
use App\Interfaces\Accurate\AccurateItemInterfaces;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateItemServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionInterfaces;
    protected $accurateTokenInterfaces;
    protected $accurateItemInterfaces;

    public function __construct(AccurateSessionInterfaces $accurateSessionInterfaces, AccurateTokenInterfaces $accurateTokenInterfaces, AccurateItemInterfaces $accurateItemInterfaces)
    {
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
        $this->accurateTokenInterfaces = $accurateTokenInterfaces;
        $this->accurateItemInterfaces = $accurateItemInterfaces;
    }

    public function getItem($code_database, $page = 1)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionInterfaces->getSessionAccurate($code_database);
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {

            $params = [
                'fields' => 'id,name,no',
                'sp.sort' => 'id|desc',
                'sp.pageSize' => 100,
                'sp.page' => $page
            ];
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respItem = sendReq('GET', $url . 'item/list.do', $params, false, false, null, $headers);

            if ($respItem['http_code'] != 200) {
                return $this->errorResponse($respItem['error'], $respItem['http_code'], errorCodes::ACC_ITM_FAILED, $respItem['error_description']);
            }
            $data = $respItem['d'];
            $meta = [
                'pageCount' => $respItem['sp']['pageCount'],
                'page' => $respItem['sp']['page'],
                'pageSize' => $respItem['sp']['pageSize'],
                'totalPage' => $respItem['sp']['rowCount'],
            ];
            return $this->successResponse($data, 200, 'Items Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_ITM_FAILED, $e->getMessage());
        }

    }

    public function getAllItem($code_database)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionInterfaces->getSessionAccurate($code_database);
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {

            $page = 1;
            $results = [];
            do {
                $params = [
                    'fields' => 'id,name,no',
                    'sp.sort' => 'id|desc',
                    'sp.pageSize' => 100,
                    'sp.page' => $page
                ];
                $headers = [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'X-Session-ID' => $session,
                ];

                $respItem = sendReq('GET', $url . 'item/list.do', $params, false, false, null, $headers);

                if ($respItem['http_code'] != 200) {
                    Log::debug($respItem);
                    return $this->errorResponse(isset($respItem['error']) ? $respItem['error'] : (isset($respItem['message']) ? $respItem['message'] : $respItem['d'][0]), $respItem['http_code'], errorCodes::ACC_ITM_FAILED, isset($respItem['error_description']) ? $respItem['error_description'] : (isset($respItem['error_detail']) ? $respItem['error_detail'] : null));
                }
                $data = $respItem['d'];
                $meta = [
                    'pageCount' => $respItem['sp']['pageCount'],
                    'page' => $respItem['sp']['page'],
                    'pageSize' => $respItem['sp']['pageSize'],
                    'totalPage' => $respItem['sp']['rowCount'],
                ];
                $results = array_merge($results, $data);

                $page++;
            } while ($page <= $meta['pageCount']);

            return $this->accurateItemInterfaces->storeItem($results, $code_database);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_ITM_FAILED, $e->getMessage());
        }

    }
}
