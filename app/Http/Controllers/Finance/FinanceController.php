<?php

namespace App\Http\Controllers\Finance;

use App\Exceptions\NotEnoughGoods;
use App\Http\Controllers\Controller;
use App\Models\Money;
use App\Models\MyModel\HandleGoods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    private $balance;


    public function __construct()
    {
        $this->balance = Money::all();
    }

    public function getFinance(Request $request)
    {
        $finance = $this->balance
            ->where('storage_id', '=', $request->storage_id)
            ->sum('size_pay');
        return response()->json(['balance' => $finance]);
    }

    public function doSpends(Request $request)
    {
        $user = Auth::id();
        $date = date('Y-m-d H:i:s');

        $transaction= new Money();
        $transaction->date = $date;
        $transaction->storage_id = $request->storage_id;
        $transaction->size_pay = -$request->size_pay;
        $transaction->description = $request->comment;
        $transaction->category = $request->category;
        $transaction->param_id = $request->param_id;
        $transaction->user_id = $user;

        if ($transaction->save()){
            return response()->json(['status'=>'ok']);
        }else{
            return response()->json(['status'=>'error']);
        }
    }


    public function doTransferMoney(Request $request){
        $request = json_decode($request->getContent());
        DB::beginTransaction();

        $finance = $this->balance
            ->where('storage_id', '=', $request->storage_id_from)
            ->sum('size_pay');

        if ($finance >= $request->amount){

            $user = Auth::id();
            $data = date('Y-m-d H:i:s');
            $give = new Money();
            $give->date = $data;
            $give->storage_id = $request->storage_id_from;
            $give->size_pay = -$request->amount;
            $give->description = $request->comment;
            $give->category = 4;

            $give->user_id = $user;
            $res = $give->save();

            $giveID = $give->id;
            if (!$res){
                DB::rollBack();
                return response()->json(
                    [
                        'message'=>'some problem with push money',
                        'status' => 'error',
                    ]);
            }

            $resive = new Money();
            $resive->date = $data;
            $resive->storage_id = $request->storage_id_to;
            $resive->size_pay = $request->amount;
            $resive->description = $request->comment;
            $resive->category = 4;
            $resive->param_id = $giveID;
            $resive->user_id = $user;
            $res = $resive->save();
            if (!$res){
                DB::rollBack();
                return response()->json(
                    [
                        'message'=>'some problem with push money',
                        'status' => 'error',
                    ]);
            }
            $resiveID = $resive->id;

             $previousTransaction = Money::findOrFail($giveID);
             $previousTransaction->param_id = $resiveID;
             $previousTransaction->save();

            DB::commit();


            return response()->json(
                [
                    'message'=>'transfer successful',
                    'status' => 'ok',
                ]);

        }else{
            DB::rollBack();
            return response()->json(
                [
                    'message'=>'not enough money on balance',
                    'status' => 'error',
                ]);
        }
    }

    public function doSale(Request $request){
        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        try {
            foreach ($request->sales as $sale){
                HandleGoods::moveGoods($sale['storage_id'], null, $sale['goods_id'], $sale['amount'],'sale', null,null, $user_id, $dateNow);


                $costProduct = (int) $sale['amount'] * (int) $sale['price'];
                $transaction = new Money();
                $transaction->date = $dateNow;
                $transaction->storage_id = $sale['storage_id'];
                $transaction->size_pay = $costProduct;
                $transaction->description = 'продажа товара '.$sale['goods_id'].',в количестве '.$sale['amount'].', по цене '. $sale['price'];
                $transaction->category = 5;
                $transaction->param_id = $sale['goods_id'];
                $transaction->user_id = $user_id;
                $transaction->save();



            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        DB::commit();

        return response()->json(['message'=>' продажа товара', 'status' => 'ok',]);
    }


    public function doBuy(Request $request){
        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        try {
            foreach ($request->buy as $buy){
                $costProduct = (int) $buy['amount'] * (int) $buy['price'];

                HandleGoods::moveGoods(null, $buy['storage_id'], $buy['goods_id'], $buy['amount'],'buy', null,null, $user_id, $dateNow, $costProduct);



                $transaction = new Money();
                $transaction->date = $dateNow;
                $transaction->storage_id = $buy['storage_id'];
                $transaction->size_pay = -$costProduct;
                $transaction->description = 'покупка товара '.$buy['goods_id'].',в количестве '.$buy['amount'].', по цене '. $buy['price'];
                $transaction->category = 6;
                $transaction->param_id = $buy['goods_id'];
                $transaction->user_id = $user_id;
                $transaction->save();



            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        DB::commit();

        return response()->json(['message'=>'покупка товара', 'status' => 'ok',]);
    }
}
