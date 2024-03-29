<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Services\Accurate\AccurateDatabaseServices;

class DatabaseController extends Controller
{
    protected $accurateDatabaseServices;
    public function __construct(AccurateDatabaseServices $accurateDatabaseServices)
    {
        $this->accurateDatabaseServices = $accurateDatabaseServices;
        $this->middleware('permission:databases-read', ['only' => ['index']]);
    }
    public function index(){
        return $this->accurateDatabaseServices->getDatabase();
    }
}
