<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Repositories\AccurateCustomerRepository;
use App\Repositories\AccurateSessionRepository;
use App\Repositories\AccurateTokenRepository;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use PHPUnit\Event\Code\Throwable;

class AccurateCustomerServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionRepository;
    protected $accurateTokenRepository;
    protected $accurateCustomerRepository;

    public function __construct(AccurateSessionRepository $accurateSessionRepository, AccurateTokenRepository $accurateTokenRepository, AccurateCustomerRepository $accurateCustomerRepository)
    {
        $this->accurateSessionRepository = $accurateSessionRepository;
        $this->accurateTokenRepository = $accurateTokenRepository;
        $this->accurateCustomerRepository = $accurateCustomerRepository;
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

            $respCustomer = sendReq('GET', config('accurate.public_url') . 'customer/list.do', $params, false, false, null, $headers);

            if ($respCustomer['http_code'] != 200) {
                return $this->errorResponse($respCustomer['error'], $respCustomer['http_code'], errorCodes::ACC_TOKEN_EXPIRED, $respCustomer['error_description']);
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
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_CUST_FAILED, $e->getMessage());
        }

    }

    public function getAllCustomer($code_database)
    {
        try {
            $url = $this->checkDatabaseAccurate($code_database);
            $session = $this->accurateSessionRepository->getSessionAccurate($code_database);
            $token = $this->accurateTokenRepository->getAccessToken();

            $page = 1;
            $results = [];
            do{
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
                    return $this->errorResponse($respCustomer['error'], $respCustomer['http_code'], errorCodes::ACC_TOKEN_EXPIRED, $respCustomer['error_description']);
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
            }while($page <= $meta['pageCount']);

            return $this->accurateCustomerRepository->storeCustomer($results, $code_database);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::ACC_CUST_FAILED, $e->getMessage());
        }

    }
}
