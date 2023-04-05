<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\SalesinvoiceRequest;
use App\Services\AccurateSalesinvoiceServices;
use Illuminate\Http\Request;

class SalesinvoiceController extends Controller
{
    protected $accurateSalesinvoiceServices;
    public function __construct(AccurateSalesinvoiceServices $accurateSalesinvoiceServices)
    {
        $this->accurateSalesinvoiceServices = $accurateSalesinvoiceServices;
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
