<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Repositories\AccurateItemRepository;
use App\Repositories\AccurateSessionRepository;
use App\Repositories\AccurateTokenRepository;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use Illuminate\Support\Facades\Log;

class AccurateItemServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionRepository;
    protected $accurateTokenRepository;
    protected $accurateItemRepository;

    public function __construct(AccurateSessionRepository $accurateSessionRepository, AccurateTokenRepository $accurateTokenRepository, AccurateItemRepository $accurateItemRepository)
    {
        $this->accurateSessionRepository = $accurateSessionRepository;
        $this->accurateTokenRepository = $accurateTokenRepository;
        $this->accurateItemRepository = $accurateItemRepository;
    }

    public function getItem($code_database, $page = 1)
    {
        $url = $this->checkDatabaseAccurate($code_database);
        $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
        $token = $this->accurateTokenRepository->getAccessToken();
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
        $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
        $token = $this->accurateTokenRepository->getAccessToken();
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
                    'X-Session-ID' => $session.'1',
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

            return $this->accurateItemRepository->storeItem($results, $code_database);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_ITM_FAILED, $e->getMessage());
        }

    }
}
