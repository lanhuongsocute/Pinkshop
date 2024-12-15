<?php

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
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::post('luubai', [\App\Http\Controllers\Api\BlogController::class, 'store'])->middleware('auth:api');
    Route::post('get_product_brand', [\App\Http\Controllers\Api\ProductController::class, 'getProductBrand'])->middleware('auth:api');
    Route::post('get_brand', [\App\Http\Controllers\Api\ProductController::class, 'getBrand'])->middleware('auth:api');
    Route::post('get_invoice', [\App\Http\Controllers\Api\WarehouseoutController::class, 'getInvoice'])->middleware(['auth:api', 'check.referer']);  
   
  });
