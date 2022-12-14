<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reports\ListGoodsMovementResource;
use App\Http\Resources\Reports\ListSalaryResource;
use App\Models\Goods;
use App\Models\Log;
use App\Models\Money;
use App\Models\Movements;
use App\Models\StockBalance;
use App\Models\Storages;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Utils;

class ReportController extends Controller
{
    public function getListGoodsMovements(Request $request){

//        return$exp = Movements::query()
//            ->addSelect([
//                'user_name_created' => User::query()->select('name')->whereColumn('user_id_created','users.id')
//            ])
//            ->addSelect([
//                'storage_name_from' => Storages::query()->select('name')->whereColumn('storage_id_from','storages.id')
//            ])
//            ->addSelect([
//                'storage_name_to' => Storages::query()->select('name')->whereColumn('storage_id_to','storages.id')
//            ])
//            ->addSelect([
//                'goods_name' => Goods::query()->select('name')->whereColumn('goods_id','goods.id')
//            ])
//            ->addSelect([
//                'goods_unit' => Goods::query()->select('unit')->whereColumn('goods_id','goods.id')
//            ])
//            ->addSelect([
//                'goods_type' => Goods::query()->select('type')->whereColumn('goods_id','goods.id')
//            ])
//            ->addSelect([
//                'user_name_accepted' => User::query()->select('name')->whereColumn('user_id_accepted','users.id')
//            ])
//            ->where('date_accepted','>=', $request->date_from)
//            ->where('date_accepted','<=', $request->date_to)
//            ->where(function($query) use ($request) {
//                $query->where('storage_id_to', '=', $request->storage_id)
//                    ->orWhere('storage_id_from', '=', $request->storage_id);
//            })
//            ->orderBy('date_accepted', 'desc')->get();
        $listGoodsMovement = Movements::query()->select()
            ->where('date_created','>=', $request->date_from)
            ->where('date_created','<=', $request->date_to)
//            ->where(function($query) use ($request) {
//                $query->where('storage_id_to', '=', $request->storage_id)
//                    ->orWhere('storage_id_from', '=', $request->storage_id);
//            })
            ->addSelect([
                'user_name_created' => User::query()->select('name')->whereColumn('user_id_created','users.id'),
                'user_name_accepted' => User::query()->select('name')->whereColumn('user_id_accepted','users.id'),
                'storage_from_name' => Storages::query()->select('name')->whereColumn('storage_id_from','storages.id'),
                'storage_to_name' => Storages::query()->select('name')->whereColumn('storage_id_to','storages.id'),
                'name' => Goods::query()->select('name')->whereColumn('goods_id','goods.id'),
                'unit' => Goods::query()->select('unit')->whereColumn('goods_id','goods.id'),
                'type' => Goods::query()->select('type')->whereColumn('goods_id','goods.id'),
            ])
            ->orderBy('date_accepted', 'desc');
//            ->limit(30)
//            ->get();
        if($request->storage_id_from == $request->storage_id_to){

            $listGoodsMovement->where(function($query){
                global $request;
               $query->where('storage_id_from', '=', $request->storage_id_from)->orwhere('storage_id_to', '=', $request->storage_id_to);
            });

        }
        elseif ($request->storage_id_from != 'null'){
            $listGoodsMovement->where('storage_id_from', '=', $request->storage_id_from);
        }
        elseif ($request->storage_id_to != 'null'){
            $listGoodsMovement->where('storage_id_to', '=', $request->storage_id_to);
        }
        if ($request->category !== 'all'){
            $listGoodsMovement->where('category', '=', $request->category);
        }

        return ListGoodsMovementResource::collection($listGoodsMovement->get());

    }

    public function getSumMoneyGoodsMovements(Request $request){
        $listGoodsMovement = Movements::all()
            ->where('storage_id_from', '=', $request->storage_id)
            ->where('date_accepted','>=', $request->date_from)
            ->where('date_accepted','<=', $request->date_to)
            ->where('category','=','move')
            ->sum(function ($item){
                return number_format($item->price * $item->amount, 2, '.', '');
        });

        return ['sum'=>$listGoodsMovement];
    }
     public function getSalary(Request $request){
         $listSalary = Money::query()->select()
             ->where('param_id', '=', $request->storage_id)
             ->where('category', '=', $request->category_id)
             ->where('date', '>=', $request->date_from)
             ->where('date', '<=', $request->date_to);

        if ($request->type === 'list'){
            return ListSalaryResource::collection($listSalary->get());
        }else{
            $totalSum = $listSalary->sum('size_pay');
            return response()->json(['sum' => $totalSum]);
        }

     }

     public function getSaldo(Request $request){
        $salary = Money::query()->select()
            ->where('param_id', '=', $request->storage_id)
            ->where('category', '=', 1)->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to)
            ->sum('size_pay');
        $spending = Money::query()->select()
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
        $amount =  0.0;
        $b = '';
        $goods = Movements::where('date_accepted', '!=', null)
            ->where('goods_id', '=', $request->goods_id)
            ->where(function($query) use ($request) {
                $query->where('storage_id_to', '=', $request->storage_id)
                    ->orWhere('storage_id_from', '=', $request->storage_id);
            })->orderBy('date_accepted', 'ASC')->get();

        foreach ($goods as $value){
            $b .= "#".$value['id'].": ";
            if($value['storage_id_to'] == $request->storage_id){
                if ($price == 0){
                    $price = $value['price'];
                }else{
                    $existValue = $price * $amount;
                    $inputValue = $value['price'] * $value['amount'];
                    $totalAmount = $amount + $value['amount'];
                    //$totalAmount = bcadd($amount, $value['amount'], 2);
                    $price = ($existValue + $inputValue)/$totalAmount;
                }

                $b .= " $amount +  ".$value['amount'];
                //  $amount +=  (float)$value['amount'];
                $amount = bcadd($amount, $value['amount'],2);
            }

            if($value['storage_id_from'] == $request->storage_id){
                $b .= " $amount -  ".$value['amount'];
                // $amount -=  (float)$value['amount'];
                $amount = bcsub($amount, $value['amount'], 2);
            }

                $b .= " = ".$amount." [".$value['storage_id_from']." -> ".$value['storage_id_to']."] total_amount: $amount \n";
        }
        if ($request->action == 'list'){
            if (isset($request->latest)){
                $latest = $request->latest;
            }else{
                $latest = 10;
            }
            $resArr = preg_split("/(\r\n|\n|\r)/",$b);
            $result = array_slice($resArr, -($latest+1), $latest);


            return response()->json($result);
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
                        if ($amount == $updateStock->amount && $amount == 0 && $updateStock->amount == 0){
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
                // ?????????????? ?????????? ???????????? ?? StockBalance
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

     public function getAdvancedLogs(Request $request){
         $this->validate($request,[
             'limit' => 'integer'
             ]);

         $movements_full = DB::table('movements_full')->orderBy('date_created', 'desc')->limit($request->limit)->get();

         return response()->json(['data' => $movements_full]);

     }

     public function getReportAboutMadeProduct(Request $request){
         $this->validate($request,[
             'storage_id'=>'integer'
         ]);
        $movements = Movements::query()
            ->select(['amount', 'price', 'id'])->where('storage_id_to', '=', $request->storage_id)
            ->where('category', '=', 'ready')
            ->where('date_created', '>=', $request->date_from)
            ->where('date_created', '<=', $request->date_to)
            ->get();
        $selfCost = null;
        $marketCost = null;
        $catMov = [];
        $resArr = [];

        foreach ($movements as $movement){
            $catMov[] = $movement->id;
            $selfCost+= $movement->amount * $movement->price;
        }

        foreach ($catMov as $value){
            $movements = Movements::query()->select(['amount', 'price'])->where('link_id', '=', $value)->where('category', '=', 'move')->get();
            if (isset($movements[0])){
                $resArr[] = $movements[0];
            }
        }

        foreach ($resArr as $item){
            $marketCost+= $item->amount * $item->price;
        }

        $profit = $marketCost - $selfCost;

        return response()->json([
            'self_cost' => number_format($selfCost,2,'.',''),
            'market_cost' => number_format($marketCost,2,'.',''),
            'profit' => number_format($profit,2,'.','')
        ]);
    }

    public function getListSales(Request $request){
//        return $sales = Money::query()
//            ->select()
//            ->addSelect([
//                'stock_price' => Movements::query()->select('price')->whereColumn('param_id','movements.id')
//            ])
//            ->addSelect([
//                'amount' => Movements::query()->select('amount')->whereColumn('param_id','movements.id')
//            ])
//            ->addSelect([
//                'goods_id' => Movements::query()->select('goods_id')->whereColumn('param_id','movements.id'),
//            ])
//            ->addSelect([
//                'user_name' => User::query()->select('name')->whereColumn('user_id','users.id')
//            ])
//            ->where('storage_id', '=', $request->storage_id)
//            ->where('category', '=', 700)
//            ->where('date', '>=', $request->df)
//            ->where('date', '<=', $request->dt)
//            ->get();
        return DB::select("select man.date as date, size_pay as size_pay,  mov.amount as amount, mov.price as stock_price, g.name as goods_name, g.unit as unit, u.name as user_name from money man left join movements mov on man.param_id = mov.id left join goods g on mov.goods_id = g.id left join users u on man.user_id = u.id where man.storage_id = {$request->storage_id} and man.category = 700 and date>='{$request->df}' and date<='{$request->dt}'");
    }
}
