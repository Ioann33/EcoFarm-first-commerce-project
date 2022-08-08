<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorageResource;
use App\Http\Resources\StoragesPropResource;
use App\Http\Resources\UserStorageResource;
use App\Models\MainStore;
use App\Models\Storages;
use App\Models\UserStorages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    public function getListMyStorages(){
        $user_id = Auth::id();
        $userStorage = UserStorages::all()
            ->where('user_id', '=', $user_id);
        return UserStorageResource::collection($userStorage);
    }

    public function getStorageProp(Request $request){
        $storage = Storages::all()
            ->where('id', '=', $request->id);
        return StoragesPropResource::collection($storage);
    }

    public function getMainStorage(){
        $mainStore = MainStore::all()
            ->where('name', '=', 'main_storage')
            ->toArray();
        return response()->json(['storage_id'=>$mainStore[0]['param']]);
    }

    public function getListStorages(){
        $storage = Storages::all();
        return StorageResource::collection($storage);
    }
}
