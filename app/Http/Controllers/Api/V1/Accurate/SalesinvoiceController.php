<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\SalesinvoiceRequest;
use App\Services\Accurate\AccurateSalesinvoiceServices;

class SalesinvoiceController extends Controller
{
    protected $accurateSalesinvoiceServices;
    public function __construct(AccurateSalesinvoiceServices $accurateSalesinvoiceServices)
    {
        $this->accurateSalesinvoiceServices = $accurateSalesinvoiceServices;
        $this->middleware('permission:sales_invoices-read', ['only' => ['index']]);
        $this->middleware('permission:sales_invoices-create', ['only' => ['store']]);
    }

    public function index(CustomerRequest $request)
    {
        return $this->accurateSalesinvoiceServices->getSalesinvoice($request);
    }
    public function store(SalesinvoiceRequest $request)
    {
        return $this->accurateSalesinvoiceServices->postSalesinvoice($request);
    }
}
