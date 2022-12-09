<?php

namespace App\Http\Controllers\Goods;

use App\Exceptions\NotEnoughGoods;
use App\Facades\GetName;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Resources\CostGoodsOnStockResource;
use App\Http\Resources\getIngredientsReasource;
use App\Http\Resources\getListGoodsMovementsOnStoragesReasource;
use App\Http\Resources\getListGoodsResource;
use App\Http\Resources\GetMovementInfoResource;
use App\Http\Resources\getMovementResource;
use App\Http\Resources\getMovementResourceReview;
use App\Http\Resources\Reports\getAllowedStoragesResource;
use App\Http\Resources\Reports\ListGoodsMovementResource;
use App\Http\Resources\StorageAllowedGoodsResource;
use App\Http\Resources\StorageGoodsPermitResource;
use App\Http\Resources\StorageGoodsResource;
use App\Http\Resources\StorageResource;
use App\Models\Goods;
use App\Models\MainStore;
use App\Models\Movements;
use App\Models\MyModel\CheckGoodsRight;
use App\Models\MyModel\HandleGoods;
use App\Models\Orders;
use App\Models\Recipe;
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
            $service->newLog('setPrice', 'set price '.$request->price.' for goods '.GetName::goods($movement->goods_id), $request->movement_id);
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





        $movement = Movements::query()->select()
            ->where($dir, '=', $request->id)
            ->where('user_id_accepted', $operator,null)
            ->addSelect([
                'user_name_created' => User::query()->select('name')->whereColumn('user_id_created','users.id'),
                'user_name_accepted' => User::query()->select('name')->whereColumn('user_id_accepted','users.id'),
                'storage_from_name' => Storages::query()->select('name')->whereColumn('storage_id_from','storages.id'),
                'storage_to_name' => Storages::query()->select('name')->whereColumn('storage_id_to','storages.id'),
                'name' => Goods::query()->select('name')->whereColumn('goods_id','goods.id'),
                'unit' => Goods::query()->select('unit')->whereColumn('goods_id','goods.id'),
                'type' => Goods::query()->select('type')->whereColumn('goods_id','goods.id'),
            ])
            ->get();
        return getMovementResourceReview::collection($movement);

//        $movement = Movements::where($dir, '=', $request->id)
//            ->where('user_id_accepted', $operator,null)->get();
//        return getMovementResource::collection($movement);
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

        $service->newLog('pullGoods', 'pull goods('.GetName::goods($movement->goods_id).'), from '.GetName::storage($movement->storage_id_from).' -> '.GetName::storage($movement->storage_id_to).', amount: '.$movement->amount. ', price: '.$movement->price, $request->movement_id);
        if($movement->save() && $result) {
            return response()->json(['status'=>'ok', 'message'=>'pull goods('.GetName::goods($movement->goods_id).'), from '.GetName::storage($movement->storage_id_from).' -> '.GetName::storage($movement->storage_id_to).', amount: '.$movement->amount. ', price: '.$movement->price]);
        }
    }

    /**
     * getStorageGoods/ {available | allowed} / {storage_id | all} / {goods_id | all}
     * getStorageGoods/{key}/{storage_id}/{goods_id?}
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function getStorageGoods(Request $request){

        global $stockBalance;
        $stockBalance = StockBalance::all();
        $goods = StorageGoods::query()->select()
            ->addSelect([
                'name' => Goods::query()->select('name')->whereColumn('goods_id','goods.id'),
                'unit' => Goods::query()->select('unit')->whereColumn('goods_id','goods.id'),
                'type' => Goods::query()->select('type')->whereColumn('goods_id','goods.id'),
                'storage_name' => Storages::query()->select('name')->whereColumn('storage_id','storages.id'),
            ]);
        if ($request->storage_id === 'all') {
            if($request->goods_id === 'all') {
                return response()->json([
                    'message'=>'нужно выбрать продукт. all - еще не реализовано',
                    'status'=> 'error'
                ]);
            }

// вывод количества выбранного продукта на складах
//            return dd($request->input());
              $goods->where('goods_id', '=', $request->goods_id)
                  ->get();
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
            $goods->where('storage_id','=', $request->storage_id);
        }else{
            $goods->where('storage_id','=', $request->storage_id)
                ->where('goods_id', '=', $request->goods_id);
        }
        if ($request->key === 'allowed'){
            $result = $goods->where('storage_id','=', $request->storage_id)
                ->get();
            return StorageAllowedGoodsResource::collection($result);
        }
        return StorageGoodsResource::collection($goods->get());

    }


    public function getStorageGoodsTwo(Request $request){

        if ($request->key === 'available') {
            $goods = DB::table('stock_balance')
                ->join('goods', 'stock_balance.goods_id', '=', 'goods.id')
                ->join('storages', 'stock_balance.storage_id', '=', 'storages.id')
                ->select(DB::raw("stock_balance.storage_id as storage_id, storages.name as storage_name, stock_balance.goods_id as goods_id, goods.name as goods_name, goods.unit, goods.type, stock_balance.price, stock_balance.amount"));
        }else{
            $goods = DB::table('storage_goods')
                ->join('goods', 'storage_goods.goods_id', '=','goods.id')
                ->join('storages', 'storage_goods.storage_id', '=','storages.id')
                ->select(DB::raw("goods.id as goods_id, goods.name as goods_name, goods.unit, goods.type as goods_type, storages.id as storage_id, storages.name as storage_name, storages.type as storage_type"));
        }

        if ($request->storage_id !== 'all'){
            $goods->where('storage_id', '=', $request->storage_id);
        }

        if ($request->goods_id !== 'all'){
            $goods->where('goods.id', '=',$request->goods_id);
        }


        return response()->json(['data' => $goods->get()]);
    }




    public function searchStorageGoods(Request $request){


        if ($request->key === 'available'){
            if ($request->storage_id === 'all') {
                $goods = DB::table('stock_balance')
                    ->join('goods', 'stock_balance.goods_id', '=','goods.id')
                    ->where('goods.name', 'like',"%$request->name%" )
                    ->join('storages', 'stock_balance.storage_id', '=','storages.id')
                    ->select(DB::raw("stock_balance.storage_id as storage_id, storages.name as storage_name, stock_balance.goods_id as goods_id, goods.name as goods_name, goods.unit, goods.type, stock_balance.price, stock_balance.amount"))
                    ->get();

            }else{
                $goods = DB::table('stock_balance')
                    ->where('storage_id', '=', $request->storage_id)
                    ->join('goods', 'stock_balance.goods_id', '=','goods.id')->where('goods.name', 'like',"%$request->name%" )
                    ->select(DB::raw("stock_balance.goods_id as goods_id, goods.name as goods_name, goods.unit, goods.type, stock_balance.price, stock_balance.amount"))
                    ->get();
            }
            return response()->json(['data' => $goods]);
        }


        if ($request->key === 'allowed'){
            if ($request->storage_id === 'all') {
                $goods = DB::table('storage_goods')
                    ->join('goods', 'storage_goods.goods_id', '=','goods.id')
                    ->where('goods.name', 'like',"%$request->name%" )
                    ->join('storages', 'storage_goods.storage_id', '=','storages.id')
                    ->select(DB::raw("goods.id as goods_id, goods.name as goods_name, goods.unit, goods.type as goods_type, storages.id as storage_id, storages.name as storage_name, storages.type as storage_type"))
                    ->get();
            }else{
                $goods = DB::table('storage_goods')
                    ->where('storage_id', '=', $request->storage_id)
                    ->join('goods', 'storage_goods.goods_id', '=','goods.id')->where('goods.name', 'like',"%$request->name%" )->select(DB::raw("goods.id as goods_id, goods.name as goods_name, goods.unit, goods.type as goods_type"))
                    ->get();
            }

            return response()->json(['data' => $goods]);
        }

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
        $permit = CheckGoodsRight::check($request->goods_id, $request->storage_id_to);
        if ($permit){
            return response()->json(['status'=>'error', 'message' => 'the goods does not allowed on this storage, you can^t push !']);
        }
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


            $service->newLog('GrowAndMove', 'grow and push goods('.GetName::goods($request->goods_id).'), storage: '.GetName::storage($request->storage_id_from).' -> '.GetName::storage($request->storage_id_to).', amount: '.$request->amount, $push['productID']);
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
            'message' => 'grow and push goods('.GetName::goods($request->goods_id).'), storage: '.GetName::storage($request->storage_id_from).' -> '.GetName::storage($request->storage_id_to).', amount: '.$request->amount

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

            $service->newLog('GrowAndMoveOnMainStorage', 'grow and push goods('.GetName::goods($request->goods_id).'), storage: '.GetName::storage($request->storage_id_from).' -> '.GetName::storage($request->storage_id_to).', amount: '.$request->amount. ', price: '. $request->price, $push['productID']);
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
            'message' => 'grow and push goods('.GetName::goods($request->goods_id).'), storage: '.GetName::storage($request->storage_id_from).' -> '.GetName::storage($request->storage_id_to).', amount: '.$request->amount. ', price: '. $request->price

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

            $movements = Movements::findOrFail($readyProductID['productID']);
            $movements->link_id = $readyProductID['productID'];
            $movements->save();

            foreach ($ingredients as $ingredient){
                if ($ingredient->goods_id === 'default'){
                    continue;
                }
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

            $service->newLog('makeProduct', 'made product '.GetName::goods($request->goods_id).' in the amount '. $request->amount.' ,self cost :'.$setSelfCostProduct->price.' and move to main storage', $readyProductID['productID']);
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json(['status'=>$e->resMess()]);
        }
        DB::commit();
        return response()->json(['status'=>'ok', 'message'=>'made product '.GetName::goods($request->goods_id).' in the amount '. $request->amount.' ,self cost :'.$setSelfCostProduct->price.' and move to main storage']);
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
        $this->validate($request, [
            'name' => 'unique:goods'
        ]);
        DB::beginTransaction();
        $addGoods = new Goods();
        $addGoods->name = $request->name;
        $addGoods->unit = $request->unit;
        $addGoods->type = $request->type;
        $addGoods->save();
        $id = $addGoods->id;
        $set = new StorageGoods();
        $mainStore = MainStore::where('name', '=', 'main_storage')
            ->get()
            ->toArray();
        $set->storage_id = $mainStore[0]['param'];
        $set->goods_id = $id;
        $set->save();
        DB::commit();
        $service->newLog('addGoods', 'added new goods '.$id.', and set permit on main storage', $id);
        return response()->json(['goods_id'=>$id, 'status'=>'ok', 'message'=>'added new goods , and set permit on main storage']);
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
            $service->newLog('setGoodsPermit', ($param ? 'set' : 'remove').' permit goods ('.GetName::goods($request->goods_id).') to storage '.GetName::storage($request->storage_id), $param);
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
        $service->newLog('doTrash','продукт ('.GetName::goods($request->goods_id).'), был утилизирован на складе ('.GetName::storage($request->storage_id).'), в количестве ('.$request->amount.')', $trashID['productID']);
        DB::commit();
        return response()->json([
            'status'=>'ok',
            'message' => 'продукт ('.GetName::goods($request->goods_id).'), был утилизирован на складе ('.GetName::storage($request->storage_id).'), в количестве ('.$request->amount.')'

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
        $goods = Goods::whereRaw('LOWER(name) like \'%'.mb_strtolower($request->name).'%\'')->get();
        return response()->json($goods);
    }

    public function correctGoods(Request $request, LogService $service){
        $user_id = Auth::id();
        $dateNow = date('Y-m-d H:i:s');

        DB::beginTransaction();

        $goods = StockBalance::where('storage_id', '=', $request->storage_id)->where('goods_id','=',$request->goods_id);
        $move = $goods->get();
        if (count($move)==0){
            HandleGoods::addGoodsOnStockBalance($request->storage_id, $request->goods_id, $request->new_amount,$dateNow, $request->price);
            $oldAmount = 0;
            $oldPrice = 0;
            $move_in = HandleGoods::movements(null, $request->storage_id , $request->goods_id, 'stock_correct', null, $request->new_amount, null, $request->price, $user_id, $dateNow);
        }else{
            if (isset($request->price)){
                $price = $request->price;
            }else{
                $price = $move[0]['price'];
            }
            $oldAmount = $move[0]['amount'];
            $oldPrice = $move[0]['price'];
            $move_out = HandleGoods::movements($request->storage_id, null, $move[0]['goods_id'], 'stock_correct', null, $move[0]['amount'], null, $price, $user_id, $dateNow);
            $goods->delete();

            if ($request->new_amount!= 0){
                $move_in = HandleGoods::movements(null, $request->storage_id , $request->goods_id, 'stock_correct', null, $request->new_amount, null, $price, $user_id, $dateNow);

                HandleGoods::addGoodsOnStockBalance($request->storage_id, $request->goods_id, $request->new_amount,$dateNow, $price);
            }

        }
        $service->newLog('correctGoods', 'correction goods ('.GetName::goods($request->goods_id).') on storage '.GetName::storage($request->storage_id).', old amount: '.$oldAmount.' -> new amount'.$request->new_amount. ' with old price: '.$oldPrice.' -> new price '.$request->price, null);
        DB::commit();
        return response()->json(['status'=> 'ok', 'message'=>'correction goods ('.GetName::goods($request->goods_id).') on storage '.GetName::storage($request->storage_id).', old amount: '.$oldAmount.' -> new amount'.$request->new_amount. ' with old price: '.$oldPrice.' -> new price '.$request->price]);
    }


    public function getIngredients(Request $request){

        $ready = Movements::find((int)$request->movement_id);
        if ($ready){
            return getIngredientsReasource::make($ready);
        }else{
            return response()->json(['status'=>'error', 'message' => 'такого товара нет']);
        }

    }

    public function getListGoodsMovementsOnStorages(Request $request){
       $movements = Movements::where('goods_id', '=', $request->goods_id)
            ->where('date_created','>=', $request->date_from)
            ->where('date_created','<=', $request->date_to)
           ->addSelect([
               'user_name_created' => User::query()->select('name')->whereColumn('user_id_created','users.id'),
               'user_name_accepted' => User::query()->select('name')->whereColumn('user_id_accepted','users.id'),
               'storage_from_name' => Storages::query()->select('name')->whereColumn('storage_id_from','storages.id'),
               'storage_to_name' => Storages::query()->select('name')->whereColumn('storage_id_to','storages.id'),
               'name' => Goods::query()->select('name')->whereColumn('goods_id','goods.id'),
               'unit' => Goods::query()->select('unit')->whereColumn('goods_id','goods.id'),
               'type' => Goods::query()->select('type')->whereColumn('goods_id','goods.id'),
           ])
           ->orderBy('date_created', 'desc')
           ->orderBy('id', 'desc')
           ->get();
       return getListGoodsMovementsOnStoragesReasource::collection($movements);
    }

    public function saveRecipe(Request $request, LogService $service){
        $request = json_decode($request->getContent());

        $checkExistRecipe = Recipe::where('readygoods_id', '=', $request->goods_id)->get();
        if (count($checkExistRecipe) !== 0){
            return response()->json(['status' => 'error', 'message' => 'current recipe already exist']);
        }
        foreach ($request->ingredients as $ingredient){
            $newRecipe = new Recipe();
            $newRecipe->readygoods_id = $request->goods_id;
            $newRecipe->ingredients_id = $ingredient->goods_id;
            $newRecipe->save();
        }
        $service->newLog('saveRecipe', 'recipe for goods '.GetName::goods($request->goods_id).' create successful', $request->goods_id);

        return response()->json(['status' => 'ok', 'message' => 'recipe for goods'.GetName::goods($request->goods_id).' create successful']);
    }

    public function getRecipe(Request $request){
        $recipe = Recipe::where('readygoods_id', '=', $request->goods_id)->get();
        return response()->json(['data'=>$recipe]);
    }

    public function updateRecipe(Request $request, LogService $service){
        DB::beginTransaction();
        $recipe = Recipe::where('readygoods_id', '=', $request->goods_id)->delete();

        foreach ($request->ingredients as $ingredient){

            $newRecipe = new Recipe();
            $newRecipe->readygoods_id = $request->goods_id;
            $newRecipe->ingredients_id = $ingredient['goods_id'];
            $newRecipe->save();
        }

        DB::commit();

        $service->newLog('updateRecipe', 'recipe for goods '.GetName::goods($request->goods_id).' updated successful', $request->goods_id);
        return response()->json(['status' => 'ok', 'message' => 'recipe for goods '.GetName::goods($request->goods_id).' updated successful']);


    }

    public function pushPackageGoods(Request $request, LogService $service){
        $request = json_decode($request->getContent());
        $user = Auth::id();
        $data = date('Y-m-d H:i:s');
        DB::beginTransaction();

        try {
            foreach ($request->move as $value){
                if (isset($value->storage_id_from)){
                    $category = 'move';
                }else{
                    $category = 'grow';
                }
                $permit = CheckGoodsRight::check($value->goods_id, $value->storage_id_to);
                if ($permit){
                    return response()->json(['status'=>'error', 'message' => 'the goods ( '.GetName::goods($value->goods_id).' ) does not allowed on this storage ( '.GetName::storage($value->storage_id_to).' ), you can^t push, operation was canceled !']);
                }
                $push = HandleGoods::moveGoods($value->storage_id_from, $value->storage_id_to, $value->goods_id, $value->amount, $category);
                $service->newLog('pushPackageGoods', $category.' goods('.GetName::goods($value->goods_id).'), storage: '.GetName::storage($value->storage_id_from).' -> '.GetName::storage($value->storage_id_to).', amount: '.$value->amount.', category '.$category, $push['productID']);
            }
        }catch (NotEnoughGoods $e){
            DB::rollBack();
            return response()->json([
                'message'=>$e->resMess(),
                'status'=> 'error'
            ]);
        }
        DB::commit();

        return response()->json(['status' => 'ok', 'message' => 'goods successful '.$category]);
    }

    public function getPermitOnBothStorages(Request $request){
        $goods_id = [];
        $goods = [];
        $goodsOnFirst = DB::table('storage_goods')
            ->where('storage_id', '=', $request->storage_id_from)
            ->join('goods', 'storage_goods.goods_id', '=','goods.id')->where('goods.name', 'like',"%$request->name%" )->select(DB::raw("goods.id as goods_id, goods.name as goods_name, goods.unit, goods.type as goods_type"))
            ->get();

        $goodsOnSecond = DB::table('storage_goods')
            ->where('storage_id', '=', $request->storage_id_to)
            ->join('goods', 'storage_goods.goods_id', '=','goods.id')->where('goods.name', 'like',"%$request->name%" )->select(DB::raw("goods.id as goods_id, goods.name as goods_name, goods.unit, goods.type as goods_type"))
            ->get();

        foreach ($goodsOnFirst as $item){
            $goodsOnSecond[] = $item;
        }

        foreach ($goodsOnSecond as $value){
            $goods_id[] = $value->goods_id;
        }

        $unique_value =  array_unique($goods_id);

        $repeat_value = array_diff_assoc($goods_id, $unique_value);

        foreach ($repeat_value as $value){
            $goods_on_stock = StockBalance::query()
                ->select(['goods_id','price','amount'])
                ->where('storage_id','=', $request->storage_id_from)
                ->where('goods_id', '=', $value)
                ->addSelect([
                    'goods_name' => Goods::query()->select('name')->whereColumn('goods_id', 'goods.id')
                ])
                ->get();

            if (count($goods_on_stock)>0){

                $goods[] = $goods_on_stock[0];
            }
        }
        return response()->json($goods);
    }

    public function getMovementsInProgress(Request $request){


        $progressMovements = Movements::query()
            ->select()
            ->where('user_id_accepted', '=', null);

        if ($request->goods_id === 'all'){
            return ListGoodsMovementResource::collection($progressMovements->get());
        }
        $progressMovements->where('goods_id', '=', $request->goods_id);


        return ListGoodsMovementResource::collection($progressMovements->get());
    }

    public function removeReady(Request $request, LogService $logService)
    {
        $dateNow = date('Y-m-d H:i:s');
        $user_id = Auth::id();
        $this->validate($request, [
            'link_id' => 'required'
        ]);

        $movementsReady = Movements::query()
            ->select()
            ->where('link_id', '=', $request->link_id)
            ->get();
        if (count($movementsReady) == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Готовая продукция (партия #' . $request->link_id . ") не существует"
            ]);
        }


        DB::beginTransaction();

        $movements_id = [];

        foreach ($movementsReady as $movement) {
/* пример данных в БД:
 category: ready
 category: ingredients
 category: ingredients
 [category: move, user_id_accepted: null | {45}] - записи в БД с категорией move может и не быть - это в случае, если мы отметили перемещение
 */
            $movements_id[] = $movement->id;

            if ($movement->category == 'move' && $movement->user_id_accepted !== null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Готовая продукция(#'.$movement->goods_id.') уже оприходована('.$movement->date_accepted.'). Удаление невозможно'
                ]);
            } elseif ($movement->category == 'move') {
                $isMove = true;
            } else {
                $isMove = false;
            }
        }


        $goods_id = '';
        $amount = '';

        foreach ($movements_id as $id) {
            $remove = Movements::findOrFail($id);
            // проверяем не была ли раньше удалена запис с movements с категорией move.
            //Если запись с категорией move была удалена ,то ready вернулся обратно на кухню и его теперь нужно удалить
//            if (count($checkMovementReady) == 0 ){
            if (!$isMove && $remove->category == 'ready')
            {
                $goods_id = $remove->goods_id;
                $amount = $remove->amount;
                HandleGoods::moveGoods($remove->storage_id_to, null, $remove->goods_id, $remove->amount, null, null, null, $user_id, $dateNow, $request->price, true);
            }
            elseif ($remove->category == 'ready')
            {
                $goods_id = $remove->goods_id;
                $amount = $remove->amount;
            }
            elseif ($remove->category == 'ingredients')
            {
                HandleGoods::addGoodsOnStockBalance($remove->storage_id_from, $remove->goods_id, $remove->amount, $dateNow, $remove->price);
            }
            $remove->delete();
        }

        $logService->newLog('removeReady', 'продукт: ' . $goods_id . ' в количестве: ' . $amount . ' был удален с перемещений ', $goods_id);
        DB::commit();
        return response()->json([
            'status' => 'ok',
            'message' => 'Готовая продукция (партия #' . $request->link_id . ") была удалена"
        ]);

    }

}
