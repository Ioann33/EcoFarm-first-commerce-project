<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderInResource;
use App\Http\Resources\StorageGoodsResource;
use App\Http\Resources\StoragesResource;
use App\Http\Resources\UserStorageResource;
use App\Models\Movements;
use App\Models\Orders;
use App\Models\StorageGoods;
use App\Models\Storages;
use App\Models\UserStorages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function getMyStorage(){
        $userStorage = UserStorages::all()->where('user_id', '=', Auth::user()->id);
        return UserStorageResource::collection($userStorage);
    }

    public function getStorageProp(Request $request){
        $storage = Storages::all()->where('id', '=', $request->id);
        return StoragesResource::collection($storage);
    }

    public function getStorageGoodsAvailable(Request $request){
        $allGoods = StorageGoods::all()->where('storage_id', '=', $request->id);

        return StorageGoodsResource::collection($allGoods);
    }

    public function getStorageOrderIn(Request $request){

        return new OrderInResource($request);

    }

    public function goodsMovementPush(Request $request){

        $newMovement = new Movements();
        $newMovement->user_id_created = Auth::id();
        $newMovement->date_created = date('Y-m-d H:i:s');
        $newMovement->storage_id_from = $request->storage_id_from;
        $newMovement->storage_id_to = $request->storage_id_to;
        $newMovement->goods_id = $request->goods_id;
        $newMovement->amount = $request->amount;
        if (isset($request->price)){
            $newMovement->price = $request->price;
        }

        if (isset($request->order_main)){
            $newMovement->order_main = $request->order_main;
            $order = Orders::findOrFail($request->order_main);
            if ($order->status === null || $order->status === 'progress'){
                $order->status = 'completed';
                $order->date_status = date('Y-m-d H:i:s');
                $order->user_id_handler = Auth::id();
                if ($order->save()){
                    echo 'status updating OK';
                }
            }else{
                return 'this order already completed or canceled';
            }
        }

        //TODO handle possible error

        if($newMovement->save()){
            echo 'Goods push OK';
        }

    }

    public function goodsMovementPull(Request $request){

        $movement = Movements::findOrFail($request->movement_id);
        $movement->user_id_accepted = Auth::id();
        $movement->date_accepted = date('Y-m-d H:i:s');
        //TODO handle possible error
        if($movement->save()){
            echo 'Goods pull OK';
        }
    }

    public function setPrice(Request $request){
        $movement = Movements::findOrFail($request->movement_id);
        $movement->price = $request->price;
        var_dump($movement->save());

        if($movement->save()) {
            echo 'Set price OK';
        }
    }
}
