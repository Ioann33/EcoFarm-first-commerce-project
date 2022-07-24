<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderInResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsResource;
use App\Http\Resources\StoragesResource;
use App\Http\Resources\UserStorageResource;
use App\Models\MainStore;
use App\Models\Movements;
use App\Models\Orders;
use App\Models\StorageGoods;
use App\Models\Storages;
use App\Models\UserStorages;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function getMyStorage(){
        $user_id = Auth::id();
        $userStorage = UserStorages::all()->where('user_id', '=', $user_id);
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
        $this->validate($request,[
            'storage_id_from'=>'required',
            'storage_id_to'=>'required',
            'goods_id'=>'required',
            'amount'=>'required',
        ]);

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
                    response()->json(['status'=>'ok']);
                }
            }else{
                return 'this order already completed or canceled';
            }
        }

        //TODO handle possible error

        if($newMovement->save()){
            return response()->json(['status'=>'ok']);
        }

    }

    public function goodsMovementPull(Request $request){

        $this->validate($request,[
            'movement_id'=>'required',
        ]);

        $movement = Movements::findOrFail($request->movement_id);
        $movement->user_id_accepted = Auth::id();
        $movement->date_accepted = date('Y-m-d H:i:s');
        //TODO handle possible error
        if($movement->save()){
            return response()->json(['status'=>'ok']);
        }
    }

    public function setPrice(Request $request){
        $this->validate($request,[
            'movement_id'=>'required',
            'price'=>'required',
        ]);

        try {
            $movement = Movements::findOrFail($request->movement_id);
        }catch (QueryException $e){
            return response()->json(['message'=>$e->getMessage()]);
        }


        $movement->price = $request->price;


        if($movement->save()) {
            return response()->json(['status'=>'ok']);
        }
    }

    public function getStorageGoodsAllowed(Request $request){

        $this->validate($request,[
            'storage_id'=>'required',
        ]);

        $goods = StorageGoods::all()->where('storage_id','=', $request->id);

        return StorageAllowedGoodsResource::collection($goods);
    }

    public function createOrder(Request $request){

        $this->validate($request,[
            'storage_id_from'=>'required',
            'storage_id_to'=>'required',
            'goods_id'=>'required',
            'amount'=>'required',
        ]);

        $newOrder = new Orders();

        $newOrder->user_id_created = Auth::id();
        $newOrder->date_created = date('Y-m-d H:i:s');
        $newOrder->storage_id_from = $request->storage_id_from;
        $newOrder->storage_id_to = $request->storage_id_to;
        $newOrder->goods_id = $request->goods_id;
        $newOrder->amount = $request->amount;

        if (isset($request->order_main)){
            $newOrder->order_main = $request->order_main;
        }

        if ($newOrder->save()){
            return response()->json(['status'=>'ok']);
        }
    }

    public function getMainStorage(){
        $mainStore = MainStore::all()->where('name', '=', 'main_storage')->toArray();
        return response()->json(['storage_id'=>$mainStore[0]['param']]);
    }
}
