<?php

namespace App\Models\MyModel;

use App\Exceptions\NotEnoughGoods;
use App\Models\Movements;
use App\Models\Orders;
use App\Models\StockBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HandleGoods
{

    /**
     * @param $storage_id_from
     * @param $storage_id_to
     * @param $goods_id
     * @param $category
     * @param $link_id
     * @param $amount
     * @param $order_main
     * @param $user
     * @param $data
     * @return \Illuminate\Http\JsonResponse|string|void
     */
    static private function movements($storage_id_from = null, $storage_id_to= null, $goods_id = null, $category = null, $link_id = null, $amount = null, $order_main = null, $pricePerUnit = null, $user = null, $data =null){
        $newMovement = new Movements();
        $newMovement->user_id_created = Auth::id();
        $newMovement->user_id_accepted = $user;
        $newMovement->date_created = date('Y-m-d H:i:s');
        $newMovement->date_accepted = $data;
        $newMovement->storage_id_from = $storage_id_from;
        $newMovement->storage_id_to = $storage_id_to;
        $newMovement->goods_id = $goods_id;
        $newMovement->category = $category;
        $newMovement->link_id = $link_id;
        $newMovement->amount = $amount;
        if (isset($pricePerUnit)){
            $newMovement->price = $pricePerUnit;
        }

        if (isset($order_main)){
            $newMovement->order_main = $order_main;
            $order = Orders::findOrFail($order_main);
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

        $newMovement->save();
        return $newMovement->id;

    }


    /**
     * @param $storage_id_from
     * @param $storage_id_to
     * @param $goods_id
     * @param $amount
     * @param $category
     * @param $link_id
     * @param $order_main
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws NotEnoughGoods
     */
    static public function moveGoods($storage_id_from = null, $storage_id_to = null, $goods_id = null, $amount = null, $category = null, $link_id = null, $order_main = null, $user = null, $data =null){

        if (isset($storage_id_from)){

            $available = StockBalance::all()
                ->where('storage_id','=',$storage_id_from)
                ->where('goods_id', '=', $goods_id)
                ->sum('amount');


            if ($amount<=$available) {
                $price = 0;
                $balance = StockBalance::all()
                    ->where('storage_id', '=', $storage_id_from)
                    ->where('goods_id', '=', $goods_id)
                    ->sort();

                $result = $amount;
                foreach ($balance as $value) {
                    if ($result <= $value->amount){


                        $price += $value->price * $result;
                        $stock = StockBalance::findOrFail($value->id);
                        $stock->amount = $value->amount - $result;

                        $stock->save();

                        $pricePerUnit = number_format($price/$amount, 2);


                        return self::movements($storage_id_from,$storage_id_to,$goods_id, $category, $link_id,$amount, $order_main, $pricePerUnit, $user, $data);

                    }else{
                        $price += $value->price * $value->amount;
                        $result-= $value->amount;
                        $emptyBox = StockBalance::findOrFail($value->id);
                        $emptyBox->delete();
                    }
                }
            }else{
                throw new NotEnoughGoods();
//                return 'not enough goods in stock';
            }
        }else{

            $user = Auth::id();
            $data = date('Y-m-d H:i:s');
            $productID = self::movements($storage_id_from,$storage_id_to,$goods_id, $category, $link_id,$amount, $order_main,null, $user, $data);

            $stockBalanceID = self::addGoodsOnStockBalance($storage_id_to, $goods_id, $amount, $data);

            return [
               'productID'=>$productID,
                'stockBalanceID'=>$stockBalanceID,
            ];
        }



    }

    /**
     * add new goods to storage"s stock balance
     * @param $storage_id
     * @param $goods_id
     * @param $amount
     * @param $date
     * @param $price
     * @return bool
     */
    static public function addGoodsOnStockBalance($storage_id, $goods_id, $amount ,  $date = null , $price = null){
        $stockBalance = new StockBalance();
        $stockBalance->storage_id = $storage_id;
        $stockBalance->goods_id = $goods_id;
        $stockBalance->price = $price;
        $stockBalance->amount = $amount;
        $stockBalance->date_accepted = $date;
        $stockBalance->save();
        return $stockBalance->id;
    }

}
