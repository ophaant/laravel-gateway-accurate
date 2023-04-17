<?php

use App\Http\Controllers\Api\V1\Accurate\AuthController;
use App\Http\Controllers\Api\V1\Accurate\CustomerController;
use App\Http\Controllers\Api\V1\Accurate\DatabaseController;
use App\Http\Controllers\Api\V1\Accurate\EmployeeController;
use App\Http\Controllers\Api\V1\Accurate\ItemController;
use App\Http\Controllers\Api\V1\Accurate\SalesinvoiceController;
use App\Http\Controllers\Api\V1\Accurate\SessionController;
use App\Http\Controllers\Api\V1\Bank\AccountBankTypeController;
use App\Http\Controllers\Api\V1\Bank\BankController;
use App\Http\Controllers\Api\V1\Bank\CategoryBankController;
use App\Http\Controllers\Api\V1\JournalVoucherUpload\JournalVoucherUploadController;
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

    Route::get('/setup', [AuthController::class,'getCode']);
    Route::get('/oauth-callback{url?}', [AuthController::class,'oauthCallback']);

    Route::prefix('accurate')->middleware('checkAccurate')->group(function (){
        Route::get('/databases', [DatabaseController::class,'index']);
        Route::get('/customers', [CustomerController::class,'getCustomer']);
        Route::post('/customers', [CustomerController::class,'store']);
        Route::get('/employees', [EmployeeController::class,'getEmployee']);
        Route::get('/items', [ItemController::class,'getItem']);
        Route::get('/sales-invoices', [SalesinvoiceController::class,'index']);
        Route::post('/sales-invoices', [SalesinvoiceController::class,'store']);
        Route::post('/refresh-token', [AuthController::class,'refreshToken']);
        Route::get('/sessions', [SessionController::class,'index']);
        Route::post('/sessions', [SessionController::class,'session']);
    });
    Route::prefix('bank')->group(function (){
        Route::apiResource('account-types', AccountBankTypeController::class)->only(['index']);
        Route::apiResource('categories', CategoryBankController::class)->except(['create', 'edit']);
        Route::apiResource('lists', BankController::class)->except(['create', 'edit']);
    });

    Route::apiResource('journal-voucher-uploads', JournalVoucherUploadController::class)->only(['index','store','destroy']);

    Route::prefix('auth')->group(function (){
        Route::post('/register', [\App\Http\Controllers\Api\V1\Auth\AuthController::class,'register'])->name('register');
        Route::post('/login', [\App\Http\Controllers\Api\V1\Auth\AuthController::class,'login'])->name('login');
        Route::post('/logout', [\App\Http\Controllers\Api\V1\Auth\AuthController::class,'logout'])->middleware('auth:api')->name('logout');
    });
});
