<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/{any}', function (){
//    return view('index');
//})->where('any','.*');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/goodsMovementPush', [\App\Http\Controllers\ApiController::class, 'goodsMovementPush'])->name('goods.movement.push');
Route::post('/goodsMovementPull', [\App\Http\Controllers\ApiController::class, 'goodsMovementPull'])->name('goods.movement.pull');
Route::post('/setPrice', [\App\Http\Controllers\ApiController::class, 'setPrice'])->name('set.price');
Route::post('/createOrder', [\App\Http\Controllers\ApiController::class, 'createOrder'])->name('create.order');
