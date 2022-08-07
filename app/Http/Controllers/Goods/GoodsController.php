<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Http\Resources\getMovementResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsResource;
use App\Models\Goods;
use App\Models\Movements;
use App\Models\MyModel\HandleGoods;
use App\Models\Orders;
use App\Models\StockBalance;
use App\Models\StorageGoods;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodsController extends Controller
{
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

//    public function goodsMovementPush(Request $request){
//        $this->validate($request,[
//            'storage_id_from'=>'required',
//            'storage_id_to'=>'required',
//            'goods_id'=>'required',
//            'amount'=>'required',
//        ]);
//
//        $newMovement = new Movements();
//        $newMovement->user_id_created = Auth::id();
//        $newMovement->date_created = date('Y-m-d H:i:s');
//        $newMovement->storage_id_from = $request->storage_id_from;
//        $newMovement->storage_id_to = $request->storage_id_to;
//        $newMovement->goods_id = $request->goods_id;
//        $newMovement->amount = $request->amount;
//        if (isset($request->price)){
//            $newMovement->price = $request->price;
//        }
//
//        if (isset($request->order_main)){
//            $newMovement->order_main = $request->order_main;
//            $order = Orders::findOrFail($request->order_main);
//            if ($order->status === null || $order->status === 'progress'){
//                $order->status = 'completed';
//                $order->date_status = date('Y-m-d H:i:s');
//                $order->user_id_handler = Auth::id();
//                if ($order->save()){
//                    response()->json(['status'=>'ok']);
//                }
//            }else{
//                return 'this order already completed or canceled';
//            }
//        }
//
//        //TODO handle possible error
//
//        if($newMovement->save()){
//            return response()->json(['status'=>'ok']);
//        }
//
//    }

    public function getMovement(Request $request){

        if($request->status === 'opened'){
            $operator = '=';
        }else{
            $operator = '!=';
        }

        if ($request->dir === 'in'){
            $dir = 'storage_id_to';
        }else{
            $dir = 'storage_id_from';
        }

        $movement = Movements::all()
            ->where($dir, '=', $request->id)
            ->where('user_id_accepted', $operator,null);

        return getMovementResource::collection($movement);
    }

    public function goodsMovementPull(Request $request){

        $this->validate($request,[
            'movement_id'=>'required',
        ]);
        $dateNow = date('Y-m-d H:i:s');

        $movement = Movements::findOrFail($request->movement_id);
        $movement->user_id_accepted = Auth::id();
        $movement->date_accepted = $dateNow;
        //TODO handle possible error
        $movement->save();

        $result = HandleGoods::addGoodsOnStockBalance($movement->storage_id_to, $movement->goods_id, $movement->amount, $dateNow, $movement->price);

        if($movement->save() && $result) {
            return response()->json(['status'=>'ok']);
        }
    }

    public function getStorageGoods(Request $request){

        if ($request->goods_id === 'all'){
            $goods = StorageGoods::all()
                ->where('storage_id','=', $request->id);
        }else{
            $goods = StorageGoods::all()
                ->where('storage_id','=', $request->id)->where('goods_id', '=', $request->goods_id);
        }

        if ($request->key === 'allowed'){

            return StorageAllowedGoodsResource::collection($goods);
        }

        return StorageGoodsResource::collection($goods);

    }


    public function stockGoodsBalance(Request $request){
        $balance = StockBalance::all()
            ->where('storage_id','=',$request->storage_id_from)
            ->where('goods_id', '=', $request->goods_id)
            ->sum('amount');
        return $balance;
    }

    public function goodsMovementPush(Request $request){

        $res = HandleGoods::moveGoods($request->storage_id_from, $request->storage_id_to, $request->goods_id, $request->amount,'move');
        if ($res === true){
            return response()->json(['status'=>'ok']);
        }else{
            return response()->json(['status'=>$res]);
        }

    }

    public function makeProduct(Request $request){

        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        $request = json_decode($request->getContent());
        $ingridients = $request->ingridients;


        $moveResult = HandleGoods::moveGoods(null, $request->storage_id, $request->goods_id, $request->amount,'ready', null, null, $user_id, $dateNow);

//        $stockResult = HandleGoods::addGoodsOnStockBalance($request->storage_id, $request->goods_id, $request->amount, $dateNow);

        foreach ($ingridients as $ingridient){
            HandleGoods::moveGoods($request->storage_id, null, $ingridient->goods_id, $ingridient->amount, 'trash', $request->goods_id, null, $user_id, $dateNow);
        }

        return response()->json(['status'=>'ok']);
    }
}
