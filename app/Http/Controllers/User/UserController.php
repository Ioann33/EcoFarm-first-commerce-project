<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\StorageGoods;
use App\Models\User;
use App\Models\UserStorages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'login'=>'required',
            'password'=>'required',
        ]);

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->login = $request->login;
        $newUser->password = Hash::make($request->password);
        $newUser->created_on = date('Y-m-d H:i:s');
        if($newUser->save()){
            return response()->json(['user_id' => $newUser->id]);
        }
    }


    public function setUserPermit(Request $request){
        if ($request->allow == 'yes'){
            $set = new UserStorages();
            $set->storage_id = $request->storage_id;
            $set->user_id = $request->user_id;
            $res = $set->save();
        }else{
            $res =  UserStorages::where('storage_id','=', $request->storage_id)
                ->where('user_id', '=', $request->user_id)->delete();

            return response()->json(['status'=>'ok', 'message' => 'пользователь ('.$request->user_id.') ,был удален со склада('.$request->storage_id.')']);
        }

        if ($res){
            return response()->json(['status'=>'ok', 'message' => 'пользователь ('.$request->user_id.') получил доступ к складу('.$request->storage_id.')']);
        }else{
            return response()->json(['status'=>'error']);
        }

    }
}
