<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderInResource;
use App\Http\Resources\StorageGoodsResource;
use App\Http\Resources\StoragesResource;
use App\Http\Resources\UserStorageResource;
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

    public function GoodsMovementPush(Request $request){

        var_dump($request->storage_id_from);
        var_dump($request->storage_id_to);
        var_dump($request->goods_id);
        var_dump($request->amount);
        var_dump(Auth::id());
    }

    public function GoodsMovementPull(Request $request){

        var_dump($request->action);
        var_dump($request->movements_id);
        var_dump(Auth::id());
        echo 'complited';

    }
}
