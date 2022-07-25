<?php

use Illuminate\Support\Facades\Route;



//Route::get('/', function () {
//    return view('app');
//});

Auth::routes();

Route::get('/{any}', function (){
    return view('app');
})->where('any', '.*');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/goodsMovementPush', [\App\Http\Controllers\ApiController::class, 'goodsMovementPush'])->name('goods.movement.push');
Route::post('/goodsMovementPull', [\App\Http\Controllers\ApiController::class, 'goodsMovementPull'])->name('goods.movement.pull');
Route::post('/setPrice', [\App\Http\Controllers\ApiController::class, 'setPrice'])->name('set.price');
Route::post('/createOrder', [\App\Http\Controllers\ApiController::class, 'createOrder'])->name('create.order');
