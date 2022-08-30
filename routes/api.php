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
    Route::post('/addStorage', [\App\Http\Controllers\Storage\StorageController::class, 'addStorage']);
    Route::post('/updateStorage', [\App\Http\Controllers\Storage\StorageController::class, 'updateStorage']);

    Route::get('/getStorageOrder/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'getStorageOrder']);

    Route::post('/goodsMovementPush', [\App\Http\Controllers\Goods\GoodsController::class, 'goodsMovementPush']);
    Route::post('/goodsMovementPull', [\App\Http\Controllers\Goods\GoodsController::class, 'goodsMovementPull']);
    Route::post('/setPrice', [\App\Http\Controllers\Goods\GoodsController::class, 'setPrice']);
    Route::get('/getListStoragesGoodsPermit/{goods_id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getListStoragesGoodsPermit']);

    Route::post('/createOrder', [\App\Http\Controllers\Orders\OrderController::class, 'createOrder']);
    Route::get('/getMainStorage', [\App\Http\Controllers\Storage\StorageController::class, 'getMainStorage']);
    Route::get('/getListOrder/{dir}/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'getListOrder']);
    Route::get('/setOrderStatus/{status}/{id}', [\App\Http\Controllers\Orders\OrderController::class, 'setOrderStatus']);
    Route::get('/getStorageGoods/{key}/{storage_id}/{goods_id?}', [\App\Http\Controllers\Goods\GoodsController::class, 'getStorageGoods']);
    Route::get('/getMovement/{dir}/{status}/{id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getMovement']);
    Route::get('/getOrder/{order_id}', [\App\Http\Controllers\Orders\OrderController::class, 'getOrder']);




    Route::get('/getGoodsStockBalance/{goods_id}', [\App\Http\Controllers\Goods\GoodsController::class, 'stockGoodsBalance']);
    Route::post('/makeProduct', [\App\Http\Controllers\Goods\GoodsController::class, 'makeProduct']);



    Route::get('/getListGoodsMovements/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getListGoodsMovements']);
    Route::get('/getSumMoneyMovementGoods/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSumMoneyMovementGoods']);
    Route::get('/getSalary/{type}/{storage_id}/{category_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSalary']);
    Route::get('/getSaldo/{storage_id}/{date_from}/{date_to}', [\App\Http\Controllers\Reports\ReportController::class, 'getSaldo']);
    Route::get('/getLogs/{event}/{date_from?}/{date_to?}', [\App\Http\Controllers\Reports\ReportController::class, 'getLogs']);
    Route::get('/checkStockBalance/{storage_id}/{goods_id}/{action?}/{latest?}', [\App\Http\Controllers\Reports\ReportController::class, 'checkStockBalance']);

    Route::get('/getFinance/{storage_id}/{type?}', [\App\Http\Controllers\Finance\FinanceController::class, 'getFinance']);
    Route::get('/costGoodsOnStock/{storage_id}/{type}', [\App\Http\Controllers\Goods\GoodsController::class, 'costGoods']);
    Route::get('/getMovementInfo/{id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getMovementInfo']);
    Route::get('/getListGoodsMovementsOnStorages/{goods_id}/{date_from}/{date_to}', [\App\Http\Controllers\Goods\GoodsController::class, 'getListGoodsMovementsOnStorages']);
    Route::get('/getListGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'getListGoods']);
    Route::get('/getIngredients/{goods_id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getIngredients']);
    Route::get('/searchGoods/{name}', [\App\Http\Controllers\Goods\GoodsController::class, 'searchGoods']);
    Route::post('/GrowAndMoveOnMainStorage', [\App\Http\Controllers\Goods\GoodsController::class, 'GrowAndMoveOnMainStorage']);
    Route::post('/GrowAndMove', [\App\Http\Controllers\Goods\GoodsController::class, 'GrowAndMove']);
    Route::post('/doTransferMoney', [\App\Http\Controllers\Finance\FinanceController::class, 'doTransferMoney']);
    Route::post('/doSpends', [\App\Http\Controllers\Finance\FinanceController::class, 'doSpends']);
    Route::post('/doSale', [\App\Http\Controllers\Finance\FinanceController::class, 'doSale']);
    Route::post('/doPreSale', [\App\Http\Controllers\Finance\FinanceController::class, 'doPreSale']);
    Route::post('/closePreSale', [\App\Http\Controllers\Finance\FinanceController::class, 'closePreSale']);
    Route::get('/getMoneyByCategoryOnStorage/{type}/{storage_id}/{category_id}/{param_id}/{date_from}/{date_to}', [\App\Http\Controllers\Finance\FinanceController::class, 'getMoneyByCategoryOnStorage']);
    Route::get('/getListMoneyByCategoryOnStorage/{storage_id}/{category_id}/{param_id}/{date_from}/{date_to}', [\App\Http\Controllers\Finance\FinanceController::class, 'getListMoneyByCategoryOnStorage']);
    Route::post('/doBuy', [\App\Http\Controllers\Finance\FinanceController::class, 'doBuy']);
    Route::post('/addGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'addGoods']);
    Route::post('/correctGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'correctGoods']);
    Route::post('/updateGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'updateGoods']);
    Route::post('/setGoodsPermit', [\App\Http\Controllers\Goods\GoodsController::class, 'setGoodsPermit']);
    Route::post('/doTrash', [\App\Http\Controllers\Goods\GoodsController::class, 'doTrash']);
    Route::post('/deleteMovement', [\App\Http\Controllers\Goods\GoodsController::class, 'deleteMovement']);
    Route::post('/saveRecipe', [\App\Http\Controllers\Goods\GoodsController::class, 'saveRecipe']);
    Route::post('/saveRecipe', [\App\Http\Controllers\Goods\GoodsController::class, 'saveRecipe']);
    Route::post('/updateRecipe', [\App\Http\Controllers\Goods\GoodsController::class, 'updateRecipe']);
    Route::post('/pushPackageGoods', [\App\Http\Controllers\Goods\GoodsController::class, 'pushPackageGoods']);
    Route::get('/getRecipe/{goods_id}', [\App\Http\Controllers\Goods\GoodsController::class, 'getRecipe']);

    Route::post('/addUser', [\App\Http\Controllers\User\UserController::class, 'addUser']);
    Route::post('/updateUser', [\App\Http\Controllers\User\UserController::class, 'updateUser']);
    Route::get('/listUsers', [\App\Http\Controllers\User\UserController::class, 'listUsers']);
    Route::get('/getUserPermit/{user_id}', [\App\Http\Controllers\User\UserController::class, 'getUserPermit']);
    Route::post('/setUserPermit', [\App\Http\Controllers\User\UserController::class, 'setUserPermit']);
    Route::get('/searchUser/{login}', [\App\Http\Controllers\User\UserController::class, 'searchUser']);

});


