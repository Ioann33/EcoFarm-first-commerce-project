<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reports\ListGoodsMovementResource;
use App\Http\Resources\Reports\ListSalaryResource;
use App\Models\Log;
use App\Models\Money;
use App\Models\Movements;
use App\Models\StockBalance;
use App\Services\LogService;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public $moneyModel;

    public $movementsModel;


    public function __construct()
    {
        $this->moneyModel = Money::all();
        $this->movementsModel = Movements::all();
    }

    public function getListGoodsMovements(Request $request){

        $listGoodsMovement = Movements::where('date_accepted','>=', $request->date_from)
            ->where('date_accepted','<=', $request->date_to)
            ->where(function($query) use ($request) {
                $query->where('storage_id_to', '=', $request->storage_id)
                    ->orWhere('storage_id_from', '=', $request->storage_id);
            })->orderBy('date_created', 'desc')->get();
        return ListGoodsMovementResource::collection($listGoodsMovement);

    }

    public function getSumMoneyMovementGoods(Request $request){
        $listGoodsMovement = $this->movementsModel
            ->where('storage_id_from', '=', $request->storage_id)
            ->where('date_accepted','>=', $request->date_from)
            ->where('date_accepted','<=', $request->date_to)
            ->where('category','=','move')
            ->sum(function ($item){
            return $item->price*$item->amount;
        });

        return ['sum'=>$listGoodsMovement];
    }
     public function getSalary(Request $request){
         $listSalary = $this->moneyModel
             ->where('param_id', '=', $request->storage_id)
             ->where('category', '=', $request->category_id)
             ->where('date', '>=', $request->date_from)
             ->where('date', '<=', $request->date_to);

        if ($request->type === 'list'){
            return ListSalaryResource::collection($listSalary);
        }else{
            $totalSum = $listSalary->sum('size_pay');
            return response()->json(['sum' => $totalSum]);
        }

     }

     public function getSaldo(Request $request){
        $salary = $this->moneyModel
            ->where('param_id', '=', $request->storage_id)
            ->where('category', '=', 1)->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to)
            ->sum('size_pay');
        $spending = $this->moneyModel
            ->where('param_id', '=', $request->storage_id)
            ->where('category', '=', 2)
            ->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to)
            ->sum('size_pay');
        $profit = $this->getSumMoneyMovementGoods($request);
        $saldo =(float) $profit['sum'] - (float) $salary - (float) $spending;
        return response()->json([
            'salary'=>$salary,
            'spending'=>$spending,
            'profit'=>$profit['sum'],
            'saldo' => $saldo,
            ]);
     }

    /**
     *
     * @param Request $request
     * @return mixed
     */
     public function checkStockBalance(Request $request, LogService $service){
         $price = 0;
         $amount = 0;
        $goods = Movements::where('date_accepted', '!=', null)
            ->where('goods_id', '=', $request->goods_id)
            ->where(function($query) use ($request) {
                $query->where('storage_id_to', '=', $request->storage_id)
                    ->orWhere('storage_id_from', '=', $request->storage_id);
            })->get();

        foreach ($goods as $value){
             if($value['storage_id_to'] == $request->storage_id){
                if ($price == 0){
                    $price = $value['price'];
                }else{
                    $existValue = $price * $amount;
                    $inputValue = $value['price']*$value['amount'];
                    $totalAmount = $amount + $value['amount'];
                    $price = ($existValue + $inputValue)/$totalAmount;
                }
                 $amount+= $value['amount'];
             }

            if($value['storage_id_from'] == $request->storage_id){
                $amount-= $value['amount'];
            }
        }


        $price = number_format($price,2,'.','');




       $stock_balance = StockBalance::where('goods_id', '=', $request->goods_id)->where('storage_id','=', $request->storage_id)->get();

        if($stock_balance->count()>0) {
            if ($amount != $stock_balance[0]['amount'] || $price != $stock_balance[0]['price']) {
                $compare = 'miss match';
            } else {

                $compare = 'match';
            }

            if ($request->action == 'fix') {

                $updateStock = StockBalance::findOrFail($stock_balance[0]['id']);
                if ($amount == $updateStock->amount){
                    $compare = 'match';
                }else{
                    $updateStock->price = $price;
                    $updateStock->amount = $amount;
                    if ($updateStock->save()) {
                        if ($amount == $updateStock->amount){
                            $updateStock->delete();
                        }
                        $compare = 'fixed';
                        $service->newLog('fixStockBalance', 'with amount: '.$stock_balance[0]['amount'].' fixed to '.$amount.', with price '.$stock_balance[0]['price'].' fixed to '.$price.' on storage '.$request->storage_id.' , goods_id '.$request->goods_id, null);
                    }
                }

            }
        }
        else
        {
            if ($request->action == 'fix') {
                // создать новую запись в StockBalance
                if ($amount == 0){

                    $compare = 'match';
                }else{
                    $newStockBalance = new StockBalance();
                    $newStockBalance->goods_id = $request->goods_id;
                    $newStockBalance->price = $price;
                    $newStockBalance->amount = $amount;
                    $newStockBalance->storage_id = $request->storage_id;
                    $newStockBalance->date_accepted = date('Y-m-d H:i:s');
                    $newStockBalance->save();

                    $compare = 'fixed';
                    $service->newLog('fixStockBalance', 'fixed amount on stock balance by movement, added on stock balance goods_id '. $request->goods_id.', in amount '. $amount.'by price '.$price, null);
                }
            }
            else

                $compare = 'miss match';
            if ($amount == 0 && count($stock_balance) == 0){
                $compare = 'match';
            }
        }


        return response()->json([
            'status'=>'ok',
            'storage_id'=>$request->storage_id,
            'goods_id'=>$request->goods_id,
            'amount'=>$amount,
            'price'=>$price,
            'compare'=>$compare
            ]);
//         select
//         *
//         from
//  "movements"
//      where
//  "date_accepted" is not null
//         and "goods_id" = '17'
//         and (
//             "storage_id_to" = '1'
//             or "storage_id_from" = '1'
//         )

     }

     public function getLogs(Request $request){
         if($request->event == 'all')
             return $logs = Log::orderBy('date', 'desc')->limit(25)->get();
             else
         return $logs = Log::where('event', '=', $request->event)->limit(25)->get();
     }
}
