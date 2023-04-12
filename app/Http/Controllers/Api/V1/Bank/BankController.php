<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Services\BankServices;
use App\Services\CategoryBankServices;

class BankController extends Controller
{
    protected $bankServices;
    public function __construct(BankServices $bankServices)
    {
        $this->bankServices = $bankServices;
    }

    public function index()
    {
        return $this->bankServices->getAll();
    }

}
