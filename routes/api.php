<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/getMyStorage', [\App\Http\Controllers\ApiController::class, 'getMyStorage']);
Route::get('/getStorageProp/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageProp']);
Route::get('/getStorageGoodsAvailable/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageGoodsAvailable']);
Route::get('/getStorageOrderIn/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageOrderIn']);

Route::post('/goodsMovementPush', [\App\Http\Controllers\ApiController::class, 'goodsMovementPush']);
Route::post('/goodsMovementPull', [\App\Http\Controllers\ApiController::class, 'goodsMovementPull']);
Route::post('/setPrice', [\App\Http\Controllers\ApiController::class, 'setPrice']);
Route::get('/getStorageGoodsAllowed/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageGoodsAllowed']);
Route::post('/createOrder', [\App\Http\Controllers\ApiController::class, 'createOrder']);
Route::get('/getMainStorage', [\App\Http\Controllers\ApiController::class, 'getMainStorage']);
