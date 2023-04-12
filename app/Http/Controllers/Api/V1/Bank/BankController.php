<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Services\Bank\BankServices;

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

    public function show($id)
    {
        return $this->bankServices->getById($id);
    }

    public function create(BankRequest $request)
    {
        return $this->bankServices->create($request->all());
    }

    public function update(BankRequest $request, $id)
    {
        return $this->bankServices->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->bankServices->delete($id);
    }

}
