<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorageResource;
use App\Http\Resources\StoragesPropResource;
use App\Http\Resources\UserStorageResource;
use App\Models\Goods;
use App\Models\MainStore;
use App\Models\Storages;
use App\Models\User;
use App\Models\UserStorages;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    public function getListMyStorages(){
        $user_id = Auth::id();
        $userStorage = UserStorages::query()
            ->select()
            ->addSelect([
                'name' => Storages::query()->select('name')->whereColumn('storage_id','storages.id'),
                'type' => Storages::query()->select('type')->whereColumn('storage_id','storages.id'),
            ])
            ->where('user_id', '=', $user_id)->get();
        return UserStorageResource::collection($userStorage);
    }

    public function getStorageProp(Request $request){
        $storage = Storages::where('id', '=', $request->id)->get();
        return StoragesPropResource::collection($storage);
    }

    public function getMainStorage(){
        $mainStore = MainStore::where('name', '=', 'main_storage')
            ->get()->toArray();
        return response()->json(['storage_id'=>$mainStore[0]['param']]);
    }

    public function getListStorages(){
        $storage = Storages::all();
        return StorageResource::collection($storage);
    }

    public function updateStorage(Request $request, LogService $service){
        $storage = Storages::findOrFail($request->storage_id);
        $oldName = $storage->name;
        $storage->name = $request->name;
        $oldType = $storage->type;
        $storage->type = $request->type;
        if ($storage->save()){
            $service->newLog('updateStorage', 'changed storage name from ('.$oldName.') to ('.$request->name.'), type from ('.$oldType.') to ('. $request->type, $request->storage_id);
            return response()->json(['status'=>'ok', 'message'=>'changed storage name from ('.$oldName.') to ('.$request->name.'), type from ('.$oldType.') to ('. $request->type]);
        }else{
            return response()->json(['status'=>'error', 'message'=>'edit storage is failed']);
        }
    }

    public function addStorage(Request $request, LogService $service){
        $newStorage = new Storages();
        $newStorage->name = $request->name;
        $newStorage->type = $request->type;

        if ($newStorage->save()){
            $service->newLog('addStorage', 'added storage '.$newStorage->id, $newStorage->id);
            return response()->json(
                [
                    'status' => 'ok',
                    'message' => "??????????({$request->name}) ?????? ????????????????. id:{$newStorage->id}",
                    'storage_id' => $newStorage->id
                ]);
        }else{
            return response()->json(
                [
                    'status' => 'error',
                    'message'=>'some errors with adding storage'
                ]
            );
        }
    }
}
