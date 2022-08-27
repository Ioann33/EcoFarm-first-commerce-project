<?php

namespace App\Http\Controllers\Goods;

use App\Exceptions\NotEnoughGoods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Resources\CostGoodsOnStockResource;
use App\Http\Resources\getListGoodsResource;
use App\Http\Resources\GetMovementInfoResource;
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
use App\Models\User;
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

        $allGoods = DB::table('goods')->orderBy('name', 'asc')->get();
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
        // @todo будем переходить на этот API: api/getListGoodsMovements/

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
     * getStorageGoods/ {available | allowed} / {storage_id | all} / {goods_id | all}
     * getStorageGoods/{key}/{storage_id}/{goods_id?}
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getStorageGoods(Request $request){

        if ($request->storage_id === 'all') {
            if($request->goods_id === 'all') {
                return response()->json([
                    'message'=>'нужно выбрать продукт. all - еще не реализовано',
                    'status'=> 'error'
                ]);
            }

// вывод количества выбранного продукта на складах
//            return dd($request->input());
              $goods = StorageGoods::where('goods_id', $request->goods_id)->get();
/*
    [
        {
            "storage_id": 1,
            "goods_id": 1,
        },
        {
            "storage_id": 2,
            "goods_id": 1,
        },
   */
            return StorageGoodsResource::collection($goods);
/*
    {
    "data": [
        {
            "storage_name": "Главный склад",
            "storage_id": 1,
            "goods_id": 1,
            "name": "помидор",
            "unit": "кг",
            "type": 1,
            "amount": 83,
            "price": "25.00",
            "goods": {
                "32": {
                    "id": 520,
                    "goods_id": 1,
                    "price": "25",
                    "amount": "83",
                    "date_accepted": "2022-08-26 15:58:09",
                    "storage_id": 1
                }
            }
        },
*/
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
            $service->newLog('moveGoods', 'push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount.' price: '.$price->price, $move['productID']);
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
            'message' => 'push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount.' price: '.$price->price

        ]);
    }
    public function GrowAndMove(Request $request, LogService $service){
        DB::beginTransaction();

        try {

            HandleGoods::moveGoods(null, $request->storage_id_from, $request->goods_id, $request->amount,'grow');

            $push = HandleGoods::moveGoods($request->storage_id_from, $request->storage_id_to, $request->goods_id, $request->amount,'move');


            $service->newLog('GrowAndMove', 'grow and push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price, $push['productID']);
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
            'message' => 'grow and push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price

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

            $service->newLog('GrowAndMoveOnMainStorage', 'grow and push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price, $push['productID']);
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
            'message' => 'grow and push goods('.$request->goods_id.'), storage: '.$request->storage_id_from.'->'.$request->storage_id_to.', amount: '.$request->amount. ', price: '. $request->price

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
            $setSelfCostProduct->price = number_format($getSelfCostProduct/$request->amount,2,'.','');
            $setSelfCostProduct->save();

            $updateMovements = Movements::findOrFail($readyProductID['productID']);
            $updateMovements->price = number_format($getSelfCostProduct/$request->amount,2,'.','');
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
//            return$costGoods = DB::table('stock_balance')->where('storage_id', '=', $request->storage_id)->join('storages', 'stock_balance.storage_id', '=', 'storages.id')->select(DB::raw("stock_balance.storage_id, sum(stock_balance.amount*stock_balance.price) as total_cost, storages.name, storages.type "))->groupBy('storage_id', 'storages.name', 'storages.type')->get();
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
        $movement = Movements::findOrFail($request->id);

        return GetMovementInfoResource::make($movement);
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

    public function updateGoods(Request $request, LogService $service){
        $item = Goods::findOrFail($request->goods_id);
        $oldName = $item->name;
        $item->name = $request->name;
        $item->unit = $request->unit;
        $item->image = $request->image;
        $item->group_id = $request->group_id;
        $oldType = $item->type;
        $item->type = $request->type;
        if ($item->save()){
            $service->newLog('updateGoods', 'changed goods name from ('.$oldName.') to ('.$request->name.'), type from ('.$oldType.') to ('. $request->type, $request->goods_id);
            return response()->json(['status'=>'ok', 'message'=>'changed goods name from ('.$oldName.') to ('.$request->name.'), type from ('.$oldType.') to ('. $request->type]);
        }else{
            return response()->json(['status'=>'error', 'message'=>'edit goods is failed']);
        }
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
            $service->newLog('setGoodsPermit', ($param ? 'set' : 'remove').' permit goods_id '.$request->goods_id.' to storage '. $request->storage_id, $param);
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

    public function deleteMovement(Request $request, LogService $service){
        $movement = Movements::findOrFail($request->movement_id);
        if ($movement->date_accepted === null){
            HandleGoods::addGoodsOnStockBalance($movement->storage_id_from, $movement->goods_id, $movement->amount, date("Y-m-d H:i:s"), $movement->price);
            $movement->delete();
            $service->newLog('deleteMovement', 'пермещение '.$request->movement_id.' было отменено', $request->movement_id);
            return response()->json(['status'=>'ok', 'message'=>'пермещение '.$request->movement_id.' было отменено']);
        }else{
            return response()->json(['status'=>'ok', 'message'=>'пермещение уже завершено  '.$request->movement_id.' , не возможно отменить']);
        }
    }

    public function searchGoods(Request $request){
//        $goods = Goods::where('name', 'like', "$request->name%")->get();
        $goods = Goods::whereRaw('LOWER(name) like \'%'.$request->name.'%\'')->get();
        return response()->json($goods);
    }

    public function correctGoods(Request $request, LogService $service){
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');

        if ($request->old_amount > $request->new_amount){

            $actualAmount = $request->old_amount - $request->new_amount ;

            $move = HandleGoods::moveGoods($request->storage_id, null, $request->goods_id, $actualAmount, 'stock_correct', null, null,$user_id, $dateNow, $request->price, null);

            $service->newLog('correctGoods', 'correction goods_id '.$request->goods_id.' on storage '.$request->storage_id.', written off amount: '.$actualAmount. ' with price: '.$request->price, $move['productID']);
            return response()->json(['status'=> 'ok', 'message'=>'correction goods_id '.$request->goods_id.' on storage '.$request->storage_id.', written off amount: '.$actualAmount. ' with price: '.$request->price]);
        }else{
            $actualAmount = $request->new_amount - $request->old_amount;

            $move = HandleGoods::movements(null, $request->storage_id, $request->goods_id, 'stock_correct', null, $actualAmount, null, $request->price, $user_id, $dateNow);


             HandleGoods::addGoodsOnStockBalance($request->storage_id, $request->goods_id, $actualAmount, $dateNow, $request->price);
            $service->newLog('correctGoods', 'correction goods_id '.$request->goods_id.' on storage '.$request->storage_id.', added amount: '.$actualAmount. ' with price: '.$request->price, $move['productID']);

            return response()->json(['status'=> 'ok', 'message'=>'correction goods_id '.$request->goods_id.' on storage '.$request->storage_id.', added amount: '.$actualAmount. ' with price: '.$request->price]);
        }
    }
}
