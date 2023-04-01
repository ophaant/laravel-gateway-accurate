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

class AccurateSalesinvoiceServices
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

    public function getSalesinvoice($request)
    {
        $url = $this->checkDatabaseAccurate($request->code_database);
        $session = $this->accurateSessionInterfaces->getSessionAccurate($request->code_database);
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {
            $params = [
                'fields' => 'id,number,customer,totalAmount,transDate,description',
                'filter.keywords.op' => 'CONTAIN',
                'filter.keywords.val' => $request->keywords??null,
                'sp.sort' => 'id|desc',
                'sp.pageSize' => 100,
                'sp.page' => $request->page??1,
            ];
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respSalesinvoice = sendReq('GET', $url . 'sales-invoice/list.do', $params, false, false, null, $headers);

            if ($respSalesinvoice['http_code'] != 200) {
                Log::debug($respSalesinvoice);
                return $this->errorResponse(isset($respSalesinvoice['error']) ? $respSalesinvoice['error'] : (isset($respSalesinvoice['message']) ? $respSalesinvoice['message'] : $respSalesinvoice['d'][0]),
                    $respSalesinvoice['http_code'], errorCodes::ACC_CUST_FAILED,
                    isset($respSalesinvoice['error_description']) ? $respSalesinvoice['error_description'] : (isset($respSalesinvoice['error_detail']) ? $respSalesinvoice['error_detail'] : null));
            }

            $data = $respSalesinvoice['d'];
            $meta = [
                'pageCount' => $respSalesinvoice['sp']['pageCount'],
                'page' => $respSalesinvoice['sp']['page'],
                'pageSize' => $respSalesinvoice['sp']['pageSize'],
                'totalPage' => $respSalesinvoice['sp']['rowCount'],
            ];
            return $this->successResponse($data, 200, 'Sales Invoice Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }

    public function postSalesinvoice($request)
    {
        $url = $this->checkDatabaseAccurate($request->code_database);
        $session = $this->accurateSessionInterfaces->getSessionAccurate($request->code_database);
        $token = $this->accurateTokenInterfaces->getAccessToken();
        try {
            $params = [
                'fields' => 'id,number,customer,totalAmount,transDate,description',
                'filter.keywords.op' => 'CONTAIN',
                'filter.keywords.val' => $request->keywords??null,
                'sp.sort' => 'id|desc',
                'sp.pageSize' => 100,
                'sp.page' => $request->page??1,
            ];
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respSalesinvoice = sendReq('GET', $url . 'sales-invoice/list.do', $params, false, false, null, $headers);

            if ($respSalesinvoice['http_code'] != 200) {
                Log::debug($respSalesinvoice);
                return $this->errorResponse(isset($respSalesinvoice['error']) ? $respSalesinvoice['error'] : (isset($respSalesinvoice['message']) ? $respSalesinvoice['message'] : $respSalesinvoice['d'][0]),
                    $respSalesinvoice['http_code'], errorCodes::ACC_CUST_FAILED,
                    isset($respSalesinvoice['error_description']) ? $respSalesinvoice['error_description'] : (isset($respSalesinvoice['error_detail']) ? $respSalesinvoice['error_detail'] : null));
            }

            $data = $respSalesinvoice['d'];
            $meta = [
                'pageCount' => $respSalesinvoice['sp']['pageCount'],
                'page' => $respSalesinvoice['sp']['page'],
                'pageSize' => $respSalesinvoice['sp']['pageSize'],
                'totalPage' => $respSalesinvoice['sp']['rowCount'],
            ];
            return $this->successResponse($data, 200, 'Sales Invoice Successfully', $meta);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }


}
