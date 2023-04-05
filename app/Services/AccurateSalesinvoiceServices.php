<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Interfaces\AccurateCustomerInterfaces;
use App\Interfaces\AccurateEmployeeInterfaces;
use App\Interfaces\AccurateSessionInterfaces;
use App\Interfaces\AccurateTokenInterfaces;
use App\Traits\ApiResponse;
use App\Traits\checkUrlAccurate;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AccurateSalesinvoiceServices
{
    use ApiResponse, checkUrlAccurate;

    protected $accurateSessionInterfaces;
    protected $accurateTokenInterfaces;
    protected $accurateCustomerInterfaces;
    protected $accurateEmployeeInterfaces;
    protected $accurateCustomerServices;

    public function __construct(AccurateSessionInterfaces $accurateSessionInterfaces, AccurateTokenInterfaces $accurateTokenInterfaces,
                                AccurateCustomerInterfaces $accurateCustomerInterfaces, AccurateEmployeeInterfaces $accurateEmployeeInterfaces,
                AccurateCustomerServices $accurateCustomerServices)
    {
        $this->accurateSessionInterfaces = $accurateSessionInterfaces;
        $this->accurateTokenInterfaces = $accurateTokenInterfaces;
        $this->accurateCustomerInterfaces = $accurateCustomerInterfaces;
        $this->accurateEmployeeInterfaces = $accurateEmployeeInterfaces;
        $this->accurateCustomerServices = $accurateCustomerServices;
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
            $nameWarehouse = $request->detailItem[0]['warehouseName'];
            $warehouseLength = Str::length($nameWarehouse);
            $firstString = Str::substr($nameWarehouse, 0, 4);
            $warehouseName = '';

            if ($firstString == 'Toko') {
                $warehouseName = ltrim(strtoupper(Str::substr($nameWarehouse, 5, $warehouseLength)));
                if ($warehouseName == 'MEGA REGENCY 1' || $warehouseName == 'MEGA REGENCY 2') {
                    $lastChar = substr($warehouseName, -1, 1);
                    $warehouseName = str_replace($lastChar, '-' . $lastChar, str_replace(' ', '', $warehouseName));
                } elseif ($warehouseName == 'KSB 1' || $warehouseName == 'KSB 2') {
                    $warehouseName = str_replace(' ', '', $warehouseName);
                } elseif ($warehouseName == 'GRD') {
                    $warehouseName = 'GARDU CIBARUSAH';
                } elseif ($warehouseName == 'GSD') {
                    $warehouseName = 'GRAHA SUKADAMI';
                } elseif ($warehouseName == 'LMK') {
                    $warehouseName = 'LINTAS';
                } elseif ($warehouseName == 'PASIR RAYA') {
                    $warehouseName = "PASIRAYA";
                }
            } elseif ($nameWarehouse == 'Sales 2') {
                $warehouseName = strtoupper($nameWarehouse);
            } elseif ($nameWarehouse == 'Sales1') {
                $lastChar = substr($nameWarehouse, -1, 1);
                $warehouseName = strtoupper(str_replace($lastChar, ' ' . $lastChar, $nameWarehouse));
            } else {
                $warehouseName = $nameWarehouse;
            }

            $employee = $this->accurateEmployeeInterfaces->getEmpByName($warehouseName, $request->code_database);

            if (!is_string($employee) && $employee->getStatusCode()!=200) {
                return $employee;
            }

            $customer_name = $request->customerName;
            $customer_no = str_replace(' ', '-', $customer_name);
            $customerNo = $this->accurateCustomerInterfaces->getCustByName($customer_no, $request->code_database);

            if (!is_string($customerNo)){
                $parameter = new \stdClass();
                $parameter->name = $customer_name;
                $parameter->customerNo = $customer_no;
                $parameter->branchName = $request->branchName;
                $parameter->categoryName = "Umum";
                $parameter->salesmanNumber = $employee;
                $parameter->code_database = $request->code_database;

                $postCust = $this->accurateCustomerServices->saveCustomer($parameter);

                if ($postCust->getStatusCode()!=200){
                    Log::error($postCust);
                    return $this->errorResponse(isset($postCust['error']) ? $postCust['error'] : (isset($postCust['message']) ? $postCust['message'] : $postCust['d'][0]),
                        $postCust['http_code'], errorCodes::ACC_CUST_FAILED,
                        isset($postCust['error_description']) ? $postCust['error_description'] : (isset($postCust['error_detail']) ? $postCust['error_detail'] : null));

                }
                $saveCustomer = $this->accurateCustomerServices->saveCustomer($parameter);
                if ($saveCustomer->getStatusCode()!=200){
                    Log::error($saveCustomer);
                    return $saveCustomer;
                }
            }

            for ($i = 0; $i < count($request->detailItem); $i++) {
                $detailItem[$i]['itemNo'] = $request->detailItem[$i]['itemNo'];
                $detailItem[$i]['unitPrice'] = $request->detailItem[$i]['unitPrice'];
                $detailItem[$i]['quantity'] = $request->detailItem[$i]['quantity'];
                $detailItem[$i]['warehouseName'] = $request->detailItem[$i]['warehouseName'];
                $detailItem[$i]['salesmanListNumber'] = array($employee);
            }

            if (!$request->has('customerNo')) {
                $request->merge(['customerNo' => $customer_no]);
            }
            $request->except('detailItem');
            $request->merge(['detailItem' => $detailItem]);
            if ($request->description == null) {
                $request->merge(['description' => '']);
            }

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Session-ID' => $session,
            ];

            $respSalesinvoice = sendReq('POST', $url . 'sales-invoice/save.do', $request, false, false, null, $headers);

            if (!$respSalesinvoice['s']) {
                Log::debug($respSalesinvoice);
                return $this->errorResponse(isset($respSalesinvoice['error']) ? $respSalesinvoice['error'] : (isset($respSalesinvoice['message']) ? $respSalesinvoice['message'] : $respSalesinvoice['d'][0]),
                    $respSalesinvoice['http_code'], errorCodes::ACC_CUST_FAILED,
                    isset($respSalesinvoice['error_description']) ? $respSalesinvoice['error_description'] : (isset($respSalesinvoice['error_detail']) ? $respSalesinvoice['error_detail'] : null));
            }
            return $this->successResponse($respSalesinvoice['d'], 200, 'Sales Invoice Successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR, $e->getMessage());
        }

    }


}
