<?php

use App\Http\Controllers\Api\V1\Accurate\AuthController;
use App\Http\Controllers\Api\V1\Accurate\CustomerController;
use App\Http\Controllers\Api\V1\Accurate\DatabaseController;
use App\Http\Controllers\Api\V1\Accurate\EmployeeController;
use App\Http\Controllers\Api\V1\Accurate\ItemController;
use App\Http\Controllers\Api\V1\Accurate\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['status' => 'success','data'=>'Gateway Accurate Version 1.0'], 200);
    });

    Route::get('/auth', [AuthController::class,'getCode']);
    Route::get('/oauth-callback{url?}', [AuthController::class,'oauthCallback']);

    Route::prefix('accurate')->middleware('checkAccurate')->group(function (){
        Route::get('/databases', [DatabaseController::class,'index']);
        Route::get('/customers', [CustomerController::class,'getCustomer']);
        Route::get('/employees', [EmployeeController::class,'getEmployee']);
        Route::get('/items', [ItemController::class,'getItem']);
        Route::post('/refresh-token', [AuthController::class,'refreshToken']);
        Route::post('/sessions', [SessionController::class,'session']);
    });
});
