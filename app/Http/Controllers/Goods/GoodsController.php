<?php

namespace App\Http\Controllers\Goods;

use App\Exceptions\NotEnoughGoods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Resources\CostGoodsOnStockResource;
use App\Http\Resources\getListGoodsResource;
use App\Http\Resources\getMovementResource;
use App\Http\Resources\Reports\getAllowedStoragesResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsPermitResource;
use App\Http\Resources\StorageGoodsResource;
use App\Http\Resources\StorageResource;
use App\Models\Goods;
use App\Models\MainStore;
use App\Models\Movements;
use App\Models\MyModel\HandleGoods;
use App\Models\Orders;
use App\Models\StockBalance;
use App\Models\StorageGoods;
use App\Models\Storages;
use App\Services\LogService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsController extends Controller
{
    /**
     * api/getListStoragesGoodsPermit/{goods_id}
     * по выбранному продукту=goods_id показать на каком складе есть разрешения
     */
    public function getListStoragesGoodsPermit(){
        $storage = Storages::all();
        return StorageGoodsPermitResource::collection($storage);

    }

    public function getListGoods(){
        $allGoods = Goods::all();
        return getListGoodsResource::collection($allGoods);
    }

    /**
     Установить цену на перемещение.
     на входе
            {
                "movement_id": 163,
                "price": "3"
            }
     */
    public function setPrice(Request $request, LogService $service){
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
            $service->newLog('setPrice', 'set price '.$request->price.' for good_id '.$movement->goods_id, $request->movement_id);
            return response()->json(
                [
                    'status' => 'ok',
                    'message' => "move($request->movement_id): установленна цена({$movement->price}) на продукт({$movement->goods_id}) "
                ]);
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

        $movement = Movements::where($dir, '=', $request->id)
            ->where('user_id_accepted', $operator,null)->get();

        return getMovementResource::collection($movement);
    }

    public function goodsMovementPull(Request $request, LogService $service){

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

        $service->newLog('pullGoods', 'pull goods('.$movement->goods_id.'), from '.$movement->storage_id_from.'->'.$movement->storage_id_to.', amount: '.$movement->amount. ', price: '.$movement->price, $request->movement_id);
        if($movement->save() && $result) {
            return response()->json(['status'=>'ok', 'message'=>'pull goods('.$movement->goods_id.'), from '.$movement->storage_id_from.'->'.$movement->storage_id_to.', amount: '.$movement->amount. ', price: '.$movement->price]);
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

    public function goodsMovementPush(Request $request, LogService $service)
    {
        DB::beginTransaction();

        try {
            $move = HandleGoods::moveGoods($request->storage_id_from, $request->storage_id_to, $request->goods_id, $request->amount,'move');
            $price = Movements::findOrFail($move['productID']);
            $service->newLog('moveGoods', 'push goods('.$request->goods_id.'), from '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount.' price: '.$price->price, $move['productID']);
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
            'message' => 'push goods('.$request->goods_id.'), from '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount.' price: '.$price->price

        ]);
    }

    public function GrowAndMoveOnMainStorage(Request $request, LogService $service){

        $dateNow = date('Y-m-d H:i:s');
        $user_id = Auth::id();



        DB::beginTransaction();

        try {

            HandleGoods::moveGoods(null, $request->storage_id_from, $request->goods_id, $request->amount,'grow');

            $push = HandleGoods::moveGoods($request->storage_id_from, $request->storage_id_to, $request->goods_id, $request->amount,'move');

            $pull = Movements::findOrFail($push['productID']);

            $pull->user_id_accepted = $user_id;
            $pull->date_accepted = $dateNow;
            $pull->price = $request->price;
            $pull->save();

            $result = HandleGoods::addGoodsOnStockBalance($pull->storage_id_to, $pull->goods_id, $pull->amount, $dateNow, $pull->price);

            $service->newLog('GrowAndMoveOnMainStorage', 'grow and push goods('.$request->goods_id.'), from storage '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price, $push['productID']);
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
            'message' => 'grow and push goods('.$request->goods_id.'), from '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price

        ]);


    }

    public function makeProduct(Request $request, LogService $service){


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

            $service->newLog('makeProduct', 'made product '.$request->goods_id.' in the amount '. $request->amount.' ,self cost :'.$setSelfCostProduct->price.' and move to main storage', $readyProductID['productID']);
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['status'=>$e->resMess()]);
        }
        DB::commit();
        return response()->json(['status'=>'ok', 'message'=>'made product '.$request->goods_id.' in the amount '. $request->amount.' ,self cost :'.$setSelfCostProduct->price.' and move to main storage']);
    }

    public function costGoods(Request $request){


        if ($request->storage_id === 'all'){
            $costGoods = StockBalance::all();

        }else{
            $costGoods = StockBalance::all()->where('storage_id', '=', $request->storage_id);
        }


        if ($request->type === 'all'){

//            return CostGoodsOnStockResource::collection($costGoods);
            $res = $costGoods->sum(function ($item){
                return $item->amount * $item->price;
            });
        }
        if ($request->type === 'ready'){
            $res = $costGoods->sum(function ($item){
                if ($item->goods->type === 2){
                    return $item->amount * $item->price;
                }
            });
        }
        if($request->type === 'ingredients'){
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

    public function addGoods(Request $request, LogService $service){
        $addGoods = new Goods();
        $addGoods->name = $request->name;
        $addGoods->unit = $request->unit;
        $addGoods->type = $request->type;
        $addGoods->save();
        $id = $addGoods->id;
        $service->newLog('addGoods', 'added new goods '.$id, $id);
        return response()->json(['goods_id'=>$id, 'status'=>'ok', 'message'=>'added new goods ']);
    }

    public function setGoodsPermit(Request $request, LogService $service){
        if ($request->allowed == 'true'){
            $set = new StorageGoods();
            $set->storage_id = $request->storage_id;
            $set->goods_id = $request->goods_id;
            $res = $set->save();
            $param = 1;
        }else{
            $res =  StorageGoods::where('storage_id','=', $request->storage_id)
                ->where('goods_id', '=', $request->goods_id)->delete();
            $param = 0;
        }
        if ($res){
            $service->newLog('setGoodsPermit', 'set permit goods_id '.$request->goods_id.' to storage '. $request->storage_id, $param);
            return response()->json(['status'=>'ok']);
        }else{
            return response()->json(['status'=>'error']);
        }

    }

    public function doTrash(Request $request, LogService $service){
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');

        DB::beginTransaction();

        try {
            $trashID = HandleGoods::moveGoods($request->storage_id, null, $request->goods_id, $request->amount,'trash', null, null, $user_id, $dateNow);
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json([
                'message'=>$e->resMess(),
                'status'=> 'error'
            ]);
        }
        $service->newLog('doTrash','продукт ('.$request->goods_id.'), был утилизирован на складе ('.$request->storage_id.'), в количестве ('.$request->amount.')', $trashID['productID']);
        DB::commit();
        return response()->json([
            'status'=>'ok',
            'message' => 'продукт ('.$request->goods_id.'), был утилизирован на складе ('.$request->storage_id.'), в количестве ('.$request->amount.')'

        ]);

    }


}
