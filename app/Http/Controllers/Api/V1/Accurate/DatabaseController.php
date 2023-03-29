<?php

namespace App\Http\Controllers\Api\V1\Accurate;

use App\Http\Controllers\Controller;
use App\Services\AccurateDatabaseServices;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    protected $accurateDatabaseServices;
    public function __construct(AccurateDatabaseServices $accurateDatabaseServices)
    {
        $this->accurateDatabaseServices = $accurateDatabaseServices;
    }
    public function index(){
        return $this->accurateDatabaseServices->getDatabase();
    }
}
