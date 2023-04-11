<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Services\AccountBankTypeServices;
use App\Services\CategoryBankServices;

class CategoryBankController extends Controller
{
    protected $categoryBankServices;
    public function __construct(CategoryBankServices $categoryBankServices)
    {
        $this->categoryBankServices = $categoryBankServices;
    }

    public function index()
    {
        return $this->categoryBankServices->getAll();
    }

}
