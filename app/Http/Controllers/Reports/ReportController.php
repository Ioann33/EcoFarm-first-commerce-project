<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reports\ListGoodsMovementResource;
use App\Http\Resources\Reports\ListSalaryResource;
use App\Models\Money;
use App\Models\Movements;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getListGoodsMovements(Request $request){

        $listGoodsMovement = Movements::all()->where('storage_id_from', '=', $request->storage_id)->where('date_accepted','>=', $request->date_from)->where('date_accepted','<=', $request->date_to);
        return ListGoodsMovementResource::collection($listGoodsMovement);

    }

    public function getSumMoneyMovementGoods(Request $request){
        $listGoodsMovement = Movements::all()
            ->where('storage_id_from', '=', $request->storage_id)
            ->where('date_accepted','>=', $request->date_from)
            ->where('date_accepted','<=', $request->date_to)
            ->sum(function ($item){
            return $item->price*$item->amount;
        });

        return response()->json(['sum'=>$listGoodsMovement]);
    }
     public function getSalary(Request $request){
         $listSalary = Money::all()->where('param_id', '=', $request->storage_id)->where('category', '=', $request->category_id)->where('date', '>=', $request->date_from)->where('date', '<=', $request->date_to);

        if ($request->type === 'list'){
            return ListSalaryResource::collection($listSalary);
        }else{
            $totalSum = $listSalary->sum('size_pay');
            return response()->json(['sum' => $totalSum]);
        }

     }
}
