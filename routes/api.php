<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/get', \App\Http\Controllers\GetController::class);
    Route::get('/getListMyStorages', [\App\Http\Controllers\Storage\StorageController::class, 'getListMyStorages']);
    Route::get('/getListStorages', [\App\Http\Controllers\Storage\StorageController::class, 'getListStorages']);
    Route::get('/getStorageProp/{id}', [\App\Http\Controllers\Storage\StorageController::class, 'getStorageProp']);

    Route::get('/getStorageOrder/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'getStorageOrder']);

    Route::post('/goodsMovementPush', [\App\Http\Controllers\Goods\GoodsController::class, 'goodsMovementPush']);
    Route::post('/goodsMovementPull', [\App\Http\Controllers\Goods\GoodsController::class, 'goodsMovementPull']);
    Route::post('/setPrice', [\App\Http\Controllers\Goods\GoodsController::class, 'setPrice']);

    Route::post('/createOrder', [\App\Http\Controllers\Orders\OrderController::class, 'createOrder']);
    Route::get('/getMainStorage', [\App\Http\Controllers\Storage\StorageController::class, 'getMainStorage']);
    Route::get('/getListOrder/{dir}/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'getListOrder']);
    Route::get('/setOrderStatus/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'setOrderStatus']);
    Route::get('/getStorageGoods/{key}/{id}/{goods_id?}', [\App\Http\Controllers\Goods\GoodsController::class, 'getStorageGoods']);
    Route::get('/getMovement/{dir}/{status}/{id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getMovement']);
    Route::get('/getOrder/{order_id}', [\App\Http\Controllers\Orders\OrderController::class, 'getOrder']);




    Route::get('/getGoodsStockBalance/{goods_id}', [\App\Http\Controllers\Goods\GoodsController::class, 'stockGoodsBalance']);
    Route::post('/makeProduct', [\App\Http\Controllers\Goods\GoodsController::class, 'makeProduct']);
//    Route::post('/gaveGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'gaveGoods']);



    Route::get('/getListGoodsMovements/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getListGoodsMovements']);
    Route::get('/getSumMoneyMovementGoods/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSumMoneyMovementGoods']);
    Route::get('/getSalary/{type}/{storage_id}/{category_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSalary']);
    Route::get('/getSaldo/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSaldo']);

    Route::get('/getFinance/{storage_id}', [\App\Http\Controllers\Finance\FinanceController::class, 'getFinance']);
    Route::get('/costGoodsOnStock/{storage_id}/{type}', [\App\Http\Controllers\Goods\GoodsController::class, 'costGoods']);
    Route::get('/getMovementInfo/{id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getMovementInfo']);
    Route::post('/doTransferMoney', [\App\Http\Controllers\Finance\FinanceController::class, 'doTransferMoney']);
    Route::post('/doSalary', [\App\Http\Controllers\Finance\FinanceController::class, 'doSalary']);
    Route::post('/doSale', [\App\Http\Controllers\Finance\FinanceController::class, 'doSale']);
    Route::post('/doBuy', [\App\Http\Controllers\Finance\FinanceController::class, 'doBuy']);

});


