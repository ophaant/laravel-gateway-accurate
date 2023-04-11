<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Services\AccountBankTypeServices;

class AccountBankTypeController extends Controller
{
    protected $accountBankTypeServices;
    public function __construct(AccountBankTypeServices $accountBankTypeServices)
    {
        $this->accountBankTypeServices = $accountBankTypeServices;
    }

    public function index()
    {
        return $this->accountBankTypeServices->getAll();
    }

}
