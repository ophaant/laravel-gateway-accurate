<?php

namespace App\Services;

use App\Helpers\errorCodes;
use App\Repositories\AccurateAuthRepository;
use App\Traits\ApiResponse;

class AccurateAuthServices
{
    use ApiResponse;

    protected $accurateTokenRepository;

    public function __construct(AccurateAuthRepository $accurateAuthRepository)
    {
        $this->accurateTokenRepository = $accurateAuthRepository;
    }

    public function getCode()
    {
        $params = [
            'response_type' => 'code',
            'client_id' => env('ACCURATE_CLIENT_ID'),
            'redirect_uri' => env('ACCURATE_REDIRECT_URL'),
            'scope' => 'bank_statement_view bank_statement_save bank_transfer_view bank_transfer_save bank_transfer_delete data_classification_view employee_payment_view employee_payment_save other_deposit_view other_deposit_save other_deposit_delete other_payment_view other_payment_save other_payment_delete expense_accrual_view expense_accrual_save expense_accrual_delete approval_view approval_save company_data comment_view comment_save attachment_view attachment_save contact_view department_view department_save department_delete project_view project_save project_delete payment_term_view payment_term_save payment_term_delete currency_view currency_save customer_view customer_save customer_delete customer_category_view customer_category_save customer_category_delete delivery_order_view delivery_order_save delivery_order_delete sales_invoice_view sales_invoice_save sales_invoice_delete salesman_commission_view salesman_commission_save sales_order_view sales_order_save sales_order_delete sales_quotation_view sales_quotation_save sales_quotation_delete exchange_invoice_view exchange_invoice_save exchange_invoice_delete sales_receipt_view sales_receipt_save sales_receipt_delete sales_return_view sales_return_save sales_return_delete glaccount_view glaccount_save glaccount_delete journal_voucher_view journal_voucher_save journal_voucher_delete shipment_view shipment_save shipment_delete tax_view tax_save tax_delete item_view item_save item_delete item_category_view item_category_save item_category_delete item_transfer_view item_transfer_save item_transfer_delete item_adjustment_view item_adjustment_save item_adjustment_delete stock_mutation_history_view job_order_view job_order_save job_order_delete material_adjustment_view material_adjustment_save material_adjustment_delete vendor_price_view vendor_price_save vendor_category_view vendor_category_save vendor_category_delete warehouse_view warehouse_save warehouse_delete purchase_invoice_view purchase_invoice_save purchase_invoice_delete purchase_order_view purchase_order_save purchase_order_delete purchase_payment_view purchase_payment_save purchase_payment_delete purchase_requisition_view purchase_requisition_save purchase_requisition_delete purchase_return_view purchase_return_save purchase_return_delete receive_item_view receive_item_save receive_item_delete vendor_view vendor_save vendor_delete fixed_asset_view fixed_asset_save fixed_asset_delete roll_over_view roll_over_save asset_transfer_view asset_transfer_save stock_opname_order_view stock_opname_order_save stock_opname_order_delete stock_opname_result_view stock_opname_result_save stock_opname_result_delete dashboard_view access_privilege_view access_privilege_save branch_view branch_save branch_delete employee_view employee_save employee_delete price_category_view price_category_save sellingprice_adjustment_view sellingprice_adjustment_save sellingprice_adjustment_delete fob_view fob_save fob_delete freeonboard_view freeonboard_save unit_view unit_save unit_delete auto_number_view auto_number_save auto_number_delete'
        ];

        $url = config('accurate.auth_url') . 'oauth/authorize';
        $url .= '?' . http_build_query($params);
        return redirect($url);
    }

    public function oauthCallback($request)
    {
        $url = $request->fullUrl();
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queryParameters);
        $code = $queryParameters['code'];

        $params = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('accurate.redirect_url'),
        ];

        $respToken = sendReq('post', config('accurate.auth_url') . 'oauth/token', $params, true, true);

        if ($respToken['http_code'] != 200) {
            return $this->errorResponse($respToken['error'], $respToken['http_code'], errorCodes::ACC_AUTH_INVALID, $respToken['error_description']);
        }

//        $respToken['expires_in'] = Carbon::now()->addSeconds($respToken['expires_in'])->toDateTimeString();

        return $this->accurateTokenRepository->storeToken($respToken);
    }
}
