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
     * @return \Illuminate\Http\JsonResponse|string|mixed
     */
    static public function movements($storage_id_from = null, $storage_id_to= null, $goods_id = null, $category = null, $link_id = null, $amount = null, $order_main = null, $pricePerUnit = null, $user = null, $data =null){
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
        return [
            'productID'=>$newMovement->id
        ];

    }


    /**
     * @param $storage_id_from
     * @param $storage_id_to
     * @param $goods_id
     * @param $amount
     * @param $category
     * @param $link_id
     * @param $order_main
     * @param $user
     * @param $data
     * @param $price
     * @param $param
     * @return array|bool|\Illuminate\Http\JsonResponse|string|void
     * @throws NotEnoughGoods
     */
    static public function moveGoods($storage_id_from = null, $storage_id_to = null, $goods_id = null, $amount = null, $category = null, $link_id = null, $order_main = null, $user = null, $data =null, $price = null, $param = null)
    {

        if (isset($storage_id_from)){

           $goodsOnStock = StockBalance::where('storage_id','=',$storage_id_from)
                ->where('goods_id', '=', $goods_id);
            $goods = $goodsOnStock->get();
            if (count($goods)==0){

                throw new NotEnoughGoods();
            }

           $available = $goodsOnStock->get('amount');


           $stockBalanceID = $goodsOnStock->get('id');



            if ($amount<=$available[0]['amount']) {

                $stock = StockBalance::findOrFail((int)$stockBalanceID[0]['id']);
                $stock->amount = (float) $available[0]['amount'] - (float) $amount;
                $stock->save();
                if ($price === null){
                    $price = $stock->price;
                }
                if ($stock->amount == 0){
                    $stock->delete();
                }
                if ($param){
                    return true;
                }
                return self::movements($storage_id_from,$storage_id_to,$goods_id, $category, $link_id,$amount, $order_main, $price, $user, $data);
            }else{
                throw new NotEnoughGoods();

            }
        }else{

            $user = Auth::id();
            $data = date('Y-m-d H:i:s');

            $productID = self::movements($storage_id_from,$storage_id_to,$goods_id, $category, $link_id,$amount, $order_main, $price, $user, $data);

            $stockBalanceID = self::addGoodsOnStockBalance($storage_id_to, $goods_id, $amount, $data, $price);

            return [
               'productID'=>$productID['productID'],
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
        $searchGoods = StockBalance::where('storage_id', '=', $storage_id)->where('goods_id', '=', $goods_id);

        if (count($searchGoods->get())>0){
            $stockBalanceID = $searchGoods->get('id');
            $stock = StockBalance::findOrFail((int)$stockBalanceID[0]['id']);
            if ($price!=null){
                $totalExistPrice = $stock->price * $stock->amount;
                $totalInputPrice = $amount*$price;

                $totalAmount = $stock->amount + $amount;

                $averagePrice = ($totalExistPrice+$totalInputPrice)/$totalAmount;
                $stock->price = number_format($averagePrice,2);

            }

            $stock->amount+=$amount;
            $stock->save();
            return $stock->id;
        }
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
