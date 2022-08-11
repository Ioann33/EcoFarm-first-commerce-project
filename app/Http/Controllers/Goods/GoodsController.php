<?php

namespace App\Http\Controllers\Goods;

use App\Exceptions\NotEnoughGoods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Resources\getMovementResource;
use App\Http\Resources\Reports\getAllowedStoragesResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsResource;
use App\Models\Goods;
use App\Models\MainStore;
use App\Models\Movements;
use App\Models\MyModel\HandleGoods;
use App\Models\Orders;
use App\Models\StockBalance;
use App\Models\StorageGoods;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    public function setPrice(Request $request){
        $this->validate($request,[
            'movement_id'=>'required',
            'price'=>'required',
        ]);

        try {
            $movement = Movements::findOrFail($request->movement_id);
        }catch (QueryException $e){
            return response()->json(['message'=>$e->getMessage()]);
        }


        $movement->price = $request->price;


        if($movement->save()) {
            return response()->json(['status'=>'ok']);
        }
    }

    public function getMovement(Request $request){

        if($request->status === 'opened'){
            $operator = '=';
        }else{
            $operator = '!=';
        }

        if ($request->dir === 'in'){
            $dir = 'storage_id_to';
        }else{
            $dir = 'storage_id_from';
        }

        $movement = Movements::all()
            ->where($dir, '=', $request->id)
            ->where('user_id_accepted', $operator,null);

        return getMovementResource::collection($movement);
    }

    public function goodsMovementPull(Request $request){

        $this->validate($request,[
            'movement_id'=>'required',
        ]);
        $dateNow = date('Y-m-d H:i:s');

        $movement = Movements::findOrFail($request->movement_id);
        $movement->user_id_accepted = Auth::id();
        $movement->date_accepted = $dateNow;
        //TODO handle possible error
        $movement->save();

        $result = HandleGoods::addGoodsOnStockBalance($movement->storage_id_to, $movement->goods_id, $movement->amount, $dateNow, $movement->price);

        if($movement->save() && $result) {
            return response()->json(['status'=>'ok']);
        }
    }

    /**
     * api/getStorageGoods/{key}/{storage_id}/{goods_id?}
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getStorageGoods(Request $request){

        if ($request->storage_id === 'all'){
            //return dd($request->input());
            return $storages = StorageGoods::where('goods_id', $request->goods_id);
            //return getAllowedStoragesResource::collection($storages);
        }

        if ($request->goods_id === 'all'){
            $goods = StorageGoods::all()
                ->where('storage_id','=', $request->storage_id);
        }else{
            $goods = StorageGoods::all()
                ->where('storage_id','=', $request->storage_id)
                ->where('goods_id', '=', $request->goods_id);
        }

        if ($request->key === 'allowed'){

            return StorageAllowedGoodsResource::collection($goods);
        }

        return StorageGoodsResource::collection($goods);

    }

    /**
     * api/getGoodsStockBalance/{goods_id}
     * Получить кол-во выбранного товара на складе
     *
     * @param Request $request
     * @return mixed
     */
    public function stockGoodsBalance(Request $request){
        $balance = StockBalance::all()
            ->where('storage_id','=',$request->storage_id_from)
            ->where('goods_id', '=', $request->goods_id)
            ->sum('amount');
        return $balance;
    }

    public function goodsMovementPush(Request $request)
    {
        DB::beginTransaction();

        try {
            HandleGoods::moveGoods($request->storage_id_from, $request->storage_id_to, $request->goods_id, $request->amount,'move');
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json([
                    'message'=>$e->resMess(),
                    'status'=> 'error'
                ]);
        }
        DB::commit();
        return response()->json([
            'status'=>'ok',
            'message' => 'push goods('.$request->goods_id.'), from '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount

        ]);
    }

    public function makeProduct(Request $request){


        DB::beginTransaction();
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');
        $request = json_decode($request->getContent());
        $ingredients = $request->ingredients;

        try {

            $readyProductID = HandleGoods::moveGoods(null, $request->storage_id, $request->goods_id, $request->amount,'ready', null, null, $user_id, $dateNow);

            foreach ($ingredients as $ingredient){

                HandleGoods::moveGoods($request->storage_id, null, $ingredient->goods_id, $ingredient->amount, 'ingredients', $readyProductID['productID'], null, $user_id, $dateNow);

            }

            $getSelfCostProduct = Movements::all()->where('link_id', '=', $readyProductID['productID'])->sum(function ($item){
                return $item->price * $item->amount;
            });

            $setSelfCostProduct = StockBalance::findOrFail($readyProductID['stockBalanceID']);
            $setSelfCostProduct->price = number_format($getSelfCostProduct/$request->amount,2);
            $setSelfCostProduct->save();

            $updateMovements = Movements::findOrFail($readyProductID['productID']);
            $updateMovements->price = number_format($getSelfCostProduct/$request->amount,2);
            $updateMovements->save();

            $mainStore = MainStore::all()
                ->where('name', '=', 'main_storage')
                ->toArray();

            HandleGoods::moveGoods($request->storage_id, $mainStore[0]['param'], $request->goods_id, $request->amount,'move', $readyProductID['productID']);

        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['status'=>$e->resMess()]);
        }
        DB::commit();
        return response()->json(['status'=>'ok']);
    }

    public function costGoods(Request $request){
//        ($request->type === 'ready') ? $type=2 : $type=1;

        $costGoods = StockBalance::all()->where('storage_id', '=', $request->storage_id);

        if ($request->type === 'ready'){
            $res = $costGoods->sum(function ($item){
                if ($item->goods->type === 2){
                    return $item->amount * $item->price;
                }
            });
        }else{
            $res = $costGoods->sum(function ($item){
                if ($item->goods->type === 1){
                    return $item->amount * $item->price;
                }
            });
        }


        return response()->json(['sum' => number_format($res, 2,'.','')]);
    }

    public function getMovementInfo(Request $request){
        return response()->json(Movements::findOrFail($request->id));
    }

    public function addGoods(Request $request){
        $addGoods = new Goods();
        $addGoods->name = $request->name;
        $addGoods->unit = $request->unit;
        $addGoods->type = $request->type;
        $addGoods->save();
        $id = $addGoods->id;
        return response()->json(['goods_id'=>$id]);
    }

    public function setGoodsPermit(Request $request){
        if ($request->allowed === 'yes'){
            $set = new StorageGoods();
            $set->storage_id = $request->storage_id;
            $set->goods_id = $request->goods_id;
            $res = $set->save();
        }else{
            $res =  StorageGoods::where('storage_id','=', $request->storage_id)
                ->where('goods_id', '=', $request->goods_id)->delete();

        }
        if ($res){
            return response()->json(['status'=>'ok']);
        }else{
            return response()->json(['status'=>'error']);
        }

    }

    public function doTrash(Request $request){
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');

        DB::beginTransaction();

        try {
            HandleGoods::moveGoods($request->storage_id, null, $request->goods_id, $request->amount,'trash', null, null, $user_id, $dateNow);
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json([
                'message'=>$e->resMess(),
                'status'=> 'error'
            ]);
        }
        DB::commit();
        return response()->json([
            'status'=>'ok',
            'message' => 'продукт ('.$request->goods_id.'), был утилизирован на складе ('.$request->storage_id.'), в количестве ('.$request->amount.')'

        ]);

    }


}
