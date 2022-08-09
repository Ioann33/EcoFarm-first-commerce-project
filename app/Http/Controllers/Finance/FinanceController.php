<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Money;
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

    public function doSalary(Request $request): array
    {
        //dd($request);
        return array('status' => 'sd');
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

}
