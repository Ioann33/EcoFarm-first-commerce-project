<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/get', \App\Http\Controllers\GetController::class);
    Route::get('/getMyStorage', [\App\Http\Controllers\ApiController::class, 'getMyStorage']);
    Route::get('/getStorageProp/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageProp']);
    Route::get('/getStorageGoodsAvailable/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageGoodsAvailable']);
    Route::get('/getStorageOrder/{status}/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageOrder']);

    Route::post('/goodsMovementPush', [\App\Http\Controllers\ApiController::class, 'goodsMovementPush']);
    Route::post('/goodsMovementPull', [\App\Http\Controllers\ApiController::class, 'goodsMovementPull']);
    Route::post('/setPrice', [\App\Http\Controllers\ApiController::class, 'setPrice']);
    Route::get('/getStorageGoodsAllowed/{id}', [\App\Http\Controllers\ApiController::class, 'getStorageGoodsAllowed']);
    Route::post('/createOrder', [\App\Http\Controllers\ApiController::class, 'createOrder']);
    Route::get('/getMainStorage', [\App\Http\Controllers\ApiController::class, 'getMainStorage']);
    Route::get('/getListOrder/{status}/{id}', [\App\Http\Controllers\ApiController::class, 'getListOrder']);
    Route::get('/setStatus/{status}/{id}', [\App\Http\Controllers\ApiController::class, 'setStatus']);
});
