<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\DiscountOutletController;
use App\Http\Controllers\DiscountProductController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fetch-product',[ProductController::class, 'fetch']);
Route::get('get-product',[ProductController::class, 'get']);
Route::get('delete-product',[ProductController::class, 'delete']);
Route::post('create-product',[ProductController::class, 'create']);
Route::post('update-product',[ProductController::class, 'update']);

Route::get('fetch-outlet',[OutletController::class, 'fetch']);
Route::get('get-outlet',[OutletController::class, 'get']);
Route::get('delete-outlet',[OutletController::class, 'delete']);
Route::post('create-outlet',[OutletController::class, 'create']);
Route::post('update-outlet',[OutletController::class, 'update']);
Route::get('testing',[OutletController::class, 'callAll']);

Route::get('fetch-discount-outlets',[DiscountOutletController::class, 'fecthDiscountOutlets']);
Route::post('create-outlet-discount',[DiscountOutletController::class, 'createDiscount']);

Route::get('fetch-discount-product',[DiscountProductController::class, 'fecthDiscountOutlets']);
Route::post('create-product-discount',[DiscountProductController::class, 'createDiscount']);

Route::get('fetch-transaction',[TransactionController::class, 'fetch']);
Route::get('get-discount-avail',[TransactionController::class, 'getOutletDiscountAvail']);
Route::get('get-product-discount',[TransactionController::class, 'getProductDiscount']);
Route::post('create-transaction',[TransactionController::class, 'addTransaction']);
