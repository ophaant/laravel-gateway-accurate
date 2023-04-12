<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryBankRequest;
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

    public function store(CategoryBankRequest $request)
    {
        return $this->categoryBankServices->store($request->all());
    }

    public function show($id)
    {
        return $this->categoryBankServices->getById($id);
    }
    public function update(CategoryBankRequest $request, $id)
    {
        return $this->categoryBankServices->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->categoryBankServices->delete($id);
    }

}
