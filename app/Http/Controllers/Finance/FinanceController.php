<?php

namespace App\Http\Controllers\Finance;

use App\Exceptions\NotEnoughGoods;
use App\Http\Controllers\Controller;
use App\Models\Money;
use App\Models\Movements;
use App\Models\MyModel\HandleGoods;
use App\Models\MyModel\MoneyTransfer;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    private $balance;


    public function __construct()
    {
//        $this->balance = Money::all();
    }

    public function getFinance(Request $request): \Illuminate\Http\JsonResponse
    {
        // понедельник 8:00

        $df = date("Y-m-d H:i:s",strtotime('last monday') + 8*60*60);
        $dt = date("Y-m-d H:i:s",strtotime('this monday') + 8*60*60);

        if($request->type != null ){
            switch ($request->type){
                case "balance":
                    $balance =  Money::where('storage_id', '=', $request->storage_id)
                        ->sum('size_pay');
                    return response()->json(
                        [
                            'balance' => $balance,
                        ]
                    );
                    break;
            }
        }


        $sales_today = Money::where('storage_id', '=', $request->storage_id)
            ->where('category',5)
            ->where('date','>=',date("Y-m-d 00:00:00"))
            ->where('date','<', date("Y-m-d 23:59:59"))
            ->sum('size_pay');

        $sales_week = Money::where('storage_id', '=', $request->storage_id)
            ->where('category',5)
            ->where('date','>=', $df)
            ->where('date','<', $dt)
            ->sum('size_pay');


        $buy_today =  Money::where('storage_id', '=', $request->storage_id)
            ->where('category',6)
            ->where('date','>=',date("Y-m-d 00:00:00"))
            ->where('date','<', date("Y-m-d 23:59:59"))
            ->sum('size_pay');

        $buy_week =  Money::where('storage_id', '=', $request->storage_id)
            ->where('category',6)
            ->where('date','>=', $df)
            ->where('date','<', $dt)
            ->sum('size_pay');

        $balance =  Money::where('storage_id', '=', $request->storage_id)
            ->sum('size_pay');

        return response()->json(
            [
                'balance' => number_format($balance,0, '.', ' '),
                'sales_today' => number_format($sales_today,0, '.', ' '),
                'sales_week' => number_format($sales_week,0, '.', ' '),
                'buy_today' => number_format($buy_today,0, '.', ' '),
                'buy_week' => number_format($buy_week,0, '.', ' '),
                'type' => $request->type
            ]
        );
    }

    public function doSpends(Request $request, LogService $service): \Illuminate\Http\JsonResponse
    {
        $user = Auth::id();
        $date = date('Y-m-d H:i:s');

        $transaction= new Money();
        $transaction->date = $date;
        $transaction->storage_id = $request->storage_id;
        $transaction->size_pay = $request->size_pay;
        $transaction->description = $request->comment;
        $transaction->category = $request->category;
        $transaction->param_id = $request->param_id;
        $transaction->user_id = $user;

        if ($transaction->save()){
            $service->newLog('doSpends', "списано {$request->size_pay}грн с департамента: {$request->storage_id}, на склад: {$request->param_id}. Категория: {$request->category}", $transaction->id);
            return response()->json(
                [
                    'status'=>'ok',
                    'message'=>"списано {$request->size_pay}грн с департамента: {$request->storage_id}, на склад: {$request->param_id}. Категория: {$request->category}"
                ]
            );
        }else{
            return response()->json(['status'=>'error']);
        }
    }


    public function doTransferMoney(Request $request, LogService $service)
    {

        DB::beginTransaction();

        $finance = Money::where('storage_id', '=', $request->storage_id_from)
            ->sum('size_pay');

        if ($finance >= $request->amount){

            $user = Auth::id();
            $data = date('Y-m-d H:i:s');
            $give = new Money();
            $give->date = $data;
            $give->storage_id = $request->storage_id_from;
            $give->size_pay = -$request->amount;
            $give->description = $request->comment;
            $give->category = 300;

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
            $resive->category = 301;
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

             $service->newLog('doTransferMoney', 'transfer money from storage '.$request->storage_id_from.' to '.$request->storage_id_to.' in the amount '. $request->amount, $giveID);

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

    /**
     * api/getSumMoneyByCategoryOnStorage/7/701/2022-06-01 00:00:00/2022-09-05 00:00:00
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSumMoneyByCategoryOnStorage(Request $request){
        if($request->storage_id == 'all')
        {
            $sum = Money::where('category', '=', $request->category_id)
                ->where('date', '>=', $request->date_from)
                ->where('date', '<=', $request->date_to)
                ->sum('size_pay');
        }
        else {
            $sum = Money::where('storage_id', '=', $request->storage_id)
                ->where('category', '=', $request->category_id)
                ->where('date', '>=', $request->date_from)
                ->where('date', '<=', $request->date_to)
                ->sum('size_pay');
        }
        return response()->json(['sum'=>$sum]);
    }
/*
{
    "sum": 0
}
*/

    /**
     * api/getListMoneyByCategoryOnStorage/12/701/2022-06-01 00:00:00/2022-09-05 00:00:00
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListMoneyByCategoryOnStorage(Request $request){
        $list = Money::where('storage_id', '=', $request->storage_id)
            ->where('category', '=', $request->category_id)
            ->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to)
            ->get();
        return response()->json(['list'=>$list]);
    }
/*
{
    "list": [
        {
            "id": 55,
            "date": "2022-08-13 14:01:35",
            "storage_id": 12,
            "size_pay": "10000",
            "description": "предпродажа товара 4,в количестве 100, по цене 100",
            "category": 701,
            "param_id": 213,
            "user_id": 1
        },
        {
            "id": 56,
            "date": "2022-08-13 14:01:35",
            "storage_id": 12,
            "size_pay": "5000",
            "description": "предпродажа товара 1,в количестве 100, по цене 50",
            "category": 701,
            "param_id": 214,
            "user_id": 1
        },
    ]
}
*/


    public function closePreSale(Request $request, LogService $service){
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        $transaction = Money::findOrFail($request->money_id);
        $movement = Movements::findOrFail($transaction->param_id);

        try {
            $selfCoastPushGoods = HandleGoods::moveGoods($transaction->storage_id, null,$movement->goods_id, $movement->amount, null, null, null, null,null, null, true);

            if ($selfCoastPushGoods){
                $movement->storage_id_from = $transaction->storage_id;
                $movement->user_id_accepted = $user_id;
                $movement->date_accepted = $dateNow;
                $movement->category = 'sale';
                $movement->save();
            }
            $transaction->category = 700;
            $transaction->save();
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        $service->newLog('closePreSale', ' отгрузка товара согласно препродаже '.$request->money_id, $request->money_id);
        DB::commit();

        return response()->json(['message'=>' отгрузка товара согласно препродаже '.$request->money_id, 'status' => 'ok',]);


    }

    public function doPreSale(Request $request, LogService $service)
    {
        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        try {
            foreach ($request->sales as $sale){

                $costProduct = (int) $sale['amount'] * (int) $sale['price'];

                $move_id = HandleGoods::movements(null, null, $sale['goods_id'],'pre_sale', null, $sale['amount'], null, $sale['price']);

                $trance_id = MoneyTransfer::moneyTransfer($costProduct, $dateNow, $sale['storage_id'], 'предпродажа товара '.$sale['goods_id'].',в количестве '.$sale['amount'].', по цене '. $sale['price'], 701, $move_id['productID'],$user_id);

                $movement = Movements::findOrFail($move_id['productID']);
                $movement->link_id = $trance_id;
                $movement->save();

                $service->newLog('doPreSale', 'предпродажа товара '.$sale['goods_id'].',в количестве '.$sale['amount'].', по цене '. $sale['price'], $trance_id);

            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        DB::commit();
        return response()->json(['message'=>' продажа товара без отгрузки', 'status' => 'ok',]);
    }

    public function doSale(Request $request, LogService $service)
    {
        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        try {
            foreach ($request->sales as $sale){
                $costProduct = (int) $sale['amount'] * (int) $sale['price'];

                $move_id = HandleGoods::moveGoods($sale['storage_id'], null, $sale['goods_id'], $sale['amount'],'sale', null,null, $user_id, $dateNow);

                $trance_id = MoneyTransfer::moneyTransfer($costProduct, $dateNow, $sale['storage_id'], 'продажа товара '.$sale['goods_id'].',в количестве '.$sale['amount'].', по цене '. $sale['price'], 700, $move_id['productID'], $user_id);

                $service->newLog('doSale', 'продажа товара '.$sale['goods_id'].',в количестве '.$sale['amount'].', по цене '. $sale['price'], $trance_id);

            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        DB::commit();

        return response()->json(['message'=>' продажа товара', 'status' => 'ok',]);
    }


    public function doBuy(Request $request, LogService $service): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        try {
            foreach ($request->buy as $buy){
                $costProduct = (int) $buy['amount'] * (int) $buy['price'];

                $move_id = HandleGoods::moveGoods(null, $buy['storage_id'], $buy['goods_id'], $buy['amount'],'buy', null,null, $user_id, $dateNow, $buy['price']);

                $trance_id = MoneyTransfer::moneyTransfer(-$costProduct, $dateNow, $buy['storage_id'], 'покупка товара '.$buy['goods_id'].',в количестве '.$buy['amount'].', по цене '. $buy['price'], 800, $move_id['productID'],$user_id);
                $service->newLog('doBuy', 'покупка товара '.$buy['goods_id'].',в количестве '.$buy['amount'].', по цене '. $buy['price'], $trance_id);

            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['message'=>$e->resMess(), 'status' => 'error',]);
        }
        DB::commit();

        return response()->json(['message'=>'покупка товара', 'status' => 'ok',]);
    }
}
