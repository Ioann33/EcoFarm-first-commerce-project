<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reports\ListGoodsMovementResource;
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
}
