<?php

namespace App\Http\Controllers\Api\V1\WhitelistIp;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhitelistRequest;
use App\Services\Whitelist\WhitelistServices;
use Illuminate\Http\Request;

class WhitelistIpController extends Controller
{
    protected $whitelistIpServices;
    public function __construct(WhitelistServices $whitelistIpServices)
    {
        $this->whitelistIpServices = $whitelistIpServices;
        $this->middleware('permission:whitelist-create', ['only' => ['store']]);
        $this->middleware('permission:whitelist-read', ['only' => ['index','show']]);
        $this->middleware('permission:whitelist-update', ['only' => ['update']]);
        $this->middleware('permission:whitelist-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return $this->whitelistIpServices->getAll();
    }

    public function store(WhitelistRequest $request)
    {
        $data = $request->all();
        return $this->whitelistIpServices->create($data);
    }

    public function show($id)
    {
        return $this->whitelistIpServices->getById($id);
    }

    public function update(WhitelistRequest $request, $id)
    {
        $data = $request->all();
        return $this->whitelistIpServices->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->whitelistIpServices->delete($id);
    }
}
