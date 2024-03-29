<?php

use App\Http\Controllers\API\Auth\AuthController as AuthAPIController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProdukTransactionController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('daftar', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

// API PRODUK
Route::get('products',[ProductController::class,'fetchData'] );
Route::get('products-category',[ProductCategoryController::class,'fetchData'] );

Route::post('daftar', [AuthAPIController::class, 'register']);
Route::post('masuk', [AuthAPIController::class, 'login']);

Route::group(['middleware' => 'api'], function () {
    Route::get('check-user', [AuthAPIController::class, 'checkUser']);
    Route::post('keluar', [AuthAPIController::class, 'logout']);
    Route::post('update', [AuthAPIController::class, 'update']);

    Route::get('transaction', [ProdukTransactionController::class, 'fetchData']);
    Route::post('checkout', [ProdukTransactionController::class, 'checkOut']);
});
