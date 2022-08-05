<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Http\Resources\getMovementResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsResource;
use App\Models\Movements;
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

        $movement = Movements::all()->where($dir, '=', $request->id)->where('user_id_accepted', $operator,null);

        return getMovementResource::collection($movement);
    }

    public function goodsMovementPull(Request $request){

        $this->validate($request,[
            'movement_id'=>'required',
        ]);

        $movement = Movements::findOrFail($request->movement_id);
        $movement->user_id_accepted = Auth::id();
        $movement->date_accepted = date('Y-m-d H:i:s');
        //TODO handle possible error
        $movement->save();

        $stockBalance = new StockBalance();
        $stockBalance->storage_id = $movement->storage_id_to;
        $stockBalance->goods_id = $movement->goods_id;
        $stockBalance->price = $movement->price;
        $stockBalance->amount = $movement->amount;
        $stockBalance->date_accepted = date('Y-m-d H:i:s');
        $stockBalance->save();

        if($movement->save() && $stockBalance->save()) {
            return response()->json(['status'=>'ok']);
        }
    }

    public function getStorageGoods(Request $request){

        if ($request->goods_id === 'all'){
            $goods = StorageGoods::all()->where('storage_id','=', $request->id);
        }else{
            $goods = StorageGoods::all()->where('storage_id','=', $request->id)->where('goods_id', '=', $request->goods_id);
        }

        if ($request->key === 'allowed'){

            return StorageAllowedGoodsResource::collection($goods);
        }

        return StorageGoodsResource::collection($goods);

    }


    public function stockGoodsBalance(Request $request){
        $balance = StockBalance::all()->where('storage_id','=',$request->storage_id_from)->where('goods_id', '=', $request->goods_id)->sum('amount');
        return $balance;
    }

    public function gaveGoods(Request $request){

        DB::beginTransaction();

        $amount = $request->amount;
        $available = $this->stockGoodsBalance($request);

        if ($request->amount<=$available) {
            $price = 0;
            $balance = StockBalance::all()->where('storage_id', '=', $request->storage_id_from)->where('goods_id', '=', $request->goods_id)->sort();

            $result = $request->amount;
            foreach ($balance as $value) {
                if ($result <= $value->amount){

                    $price += $value->price * $result;
                    $stock = StockBalance::findOrFail($value->id);
                    $stock->amount = $value->amount - $result;



                    $pricePerUnit = $price/$amount;

                    $newMovement = new Movements();
                    $newMovement->user_id_created = Auth::id();
                    $newMovement->date_created = date('Y-m-d H:i:s');
                    $newMovement->storage_id_from = $request->storage_id_from;
                    $newMovement->storage_id_to = $request->storage_id_to;
                    $newMovement->goods_id = $request->goods_id;
                    $newMovement->amount = $amount;
                    if (isset($pricePerUnit)){
                        $newMovement->price = $pricePerUnit;
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
                            } else DB::rollBack();
                        }else{
                            return 'this order already completed or canceled';
                        }
                    }

                    //TODO handle possible error

                    if($newMovement->save()){

                        return response()->json(['status'=>'ok']);
                    }else DB::rollBack();


                }else{
                    $price += $value->price * $value->amount;
                    $result-= $value->amount;
                    $emptyBox = StockBalance::findOrFail($value->id);
                    $emptyBox->delete();
                }
            }
        }else{
            return 'not enough goods in stock';
        }
    }

}
