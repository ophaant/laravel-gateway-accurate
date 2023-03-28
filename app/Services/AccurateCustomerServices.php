<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Repositories\AccurateSessionRepository;
use App\Repositories\AccurateTokenRepository;
use App\Traits\ApiResponse;
use Exception;

class AccurateCustomerServices
{
    use ApiResponse;

    protected $accurateSessionRepository;
    protected $accurateTokenRepository;

    public function __construct(AccurateSessionRepository $accurateSessionRepository, AccurateTokenRepository $accurateTokenRepository)
    {
        $this->accurateSessionRepository = $accurateSessionRepository;
        $this->accurateTokenRepository = $accurateTokenRepository;
    }

    public function getCustomer($code_database, $page = 1)
    {
        try {

            $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
            $token = $this->accurateTokenRepository->getAccessToken();

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
//        $hit = Http::withHeaders($headers)->get(config('accurate.public_url') . 'customer/list.do', $params);
//        dd($hit->json());
            $respCustomer = sendReq('GET', config('accurate.public_url') . 'customer/list.do', $params, false, false, null, $headers);
            $data = $respCustomer['d'];
            $meta = [
                'pageCount' => $respCustomer['sp']['pageCount'],
                'page' => $respCustomer['sp']['page'],
                'pageSize' => $respCustomer['sp']['pageSize'],
                'totalPage' => $respCustomer['sp']['rowCount'],
            ];

            if ($respCustomer['http_code'] != 200) {
                return $this->errorResponse($respCustomer['error'], $respCustomer['http_code'], errorCodes::ACC_TOKEN_EXPIRED, $respCustomer['error_description']);
            }

            return $this->successResponse($data, 200, 'Customers Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_CUST_FAILED, $e->getMessage());
        }

    }
}
