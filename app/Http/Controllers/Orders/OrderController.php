<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\getListOrderAllResource;
use App\Http\Resources\getListOrderOpenedResource;
use App\Http\Resources\getListOrderProcessedResource;
use App\Http\Resources\OrderInResource;
use App\Http\Resources\OrderResource;
use App\Models\Orders;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $allOrdersModel;

    public function __construct()
    {
        $this->allOrdersModel = Orders::all();
    }

    public function getListOrder(Request $request){

        if($request->dir === 'in'){
            $dir = 'storage_id_to';
        }else{
            $dir = 'storage_id_from';
        }

        if ($request->status === 'opened'){
            $orderList = $this->allOrdersModel
                ->where('status','=', null)
                ->where($dir, '=', $request->id);

            return getListOrderOpenedResource::collection($orderList);
        }


        if ($request->status === 'canceled'){
            $orderList = $this->allOrdersModel
                ->where('status','=', 'canceled')
                ->where($dir, '=', $request->id);

            return getListOrderProcessedResource::collection($orderList);
        }

        if ($request->status === 'progress'){
            $orderList = $this->allOrdersModel
                ->where('status','=', 'progress')
                ->where($dir, '=', $request->id);

            return getListOrderProcessedResource::collection($orderList);
        }

        if ($request->status === 'completed'){
            $orderList = $this->allOrdersModel
                ->where('status','=', 'completed')
                ->where($dir, '=', $request->id);

            return getListOrderProcessedResource::collection($orderList);
        }

        if ($request->status === 'all'){
            $orderList = $this->allOrdersModel
                ->where($dir, '=', $request->id);

            return getListOrderAllResource::collection($orderList);
        }


    }

    public function createOrder(Request $request, LogService $service){

        $this->validate($request,[
            'storage_id_from'=>'required',
            'storage_id_to'=>'required',
            'goods_id'=>'required',
            'amount'=>'required',
        ]);

        $newOrder = new Orders();

        $newOrder->user_id_created = Auth::id();
        $newOrder->date_created = date('Y-m-d H:i:s');
        $newOrder->storage_id_from = $request->storage_id_from;
        $newOrder->storage_id_to = $request->storage_id_to;
        $newOrder->goods_id = $request->goods_id;
        $newOrder->amount = $request->amount;
        if (isset($request->order_main)){
            $newOrder->order_main = $request->order_main;
        }

        if (isset($request->order_main)){
            $newOrder->order_main = $request->order_main;
        }

        if ($newOrder->save()){
            $service->newLog('createOrder', 'create order '.$newOrder->id, $newOrder->id);
            return response()->json(['status'=>'ok']);
        }
    }

    public function getStorageOrder(Request $request){

        return new OrderInResource($request);

    }

    public function setOrderStatus(Request $request, LogService $service){

        $order = Orders::findOrFail($request->id);
        $order->status = $request->status;
        $order->date_status = date('Y-m-d H:i:s');
        $order->user_id_handler = Auth::id();
        if($order->save()) {
            $service->newLog('setOrderStatus', 'set order status '. $request->status, $request->id);
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'some problem with setStatus']);
        }

    }

    public function getOrder(Request $request){
        $order = Orders::findOrFail($request->order_id);
        return OrderResource::make($order);
    }

}
