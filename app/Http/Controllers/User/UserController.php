<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersPermitResource;
use App\Models\StorageGoods;
use App\Services\LogService;
use App\Models\Storages;
use App\Models\User;
use App\Models\UserStorages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser(Request $request, LogService $service){
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
            $service->newLog('addUser', 'added new user, login: '.$request->login.' name: '.$request->name, $newUser->id);
            return response()->json(
                [
                    'status' => 'ok',
                    'message' => "польователь #{$newUser->id} {$request->login}($request->name) был добавлен в базу ",
                    'user_id' => $newUser->id
                ]
            );
        } else
            return response()->json(
                [
                    'status' => 'error',
                    'message' => "при добавление пользоваеля возникла ошибка",
                    'user_id' => $newUser->id
                ]
            );
    }

    public function getUserPermit(Request $request){
//        $this->validate($request,[
//            'user_id'=>'required',
//        ]);

        $storage = Storages::all();
        return UsersPermitResource::collection($storage);

    }

    public function listUsers(Request $request){
        $all_users = User::all();
        return $all_users;
/* return
--------------------json
[
    {
        "id": 3,
        "name": "Официант",
        "login": "Официант",
        "created_on": "2022-07-20 15:11:55",
        "last_login": null
    },
    {
        "id": 1,
        "name": "Djek Lu",
        "login": "djeklu",
        "created_on": "2022-07-19 22:20:58",
        "last_login": null
    },
]
--------------------
*/
    }

    public function updateUser(Request $request, LogService $service){
        $user = User::findOrFail($request->user_id);
        $oldName = $user->name;
        $user->name = $request->name;
        $oldLogin = $user->login;
        $user->login = $request->login;

        $user->password = Hash::make($request->password);
        if($user->save()){
            $service->newLog('updateUser', 'changed name from ('.$oldName.') to ('.$request->name.'), login from ('.$oldLogin.') to ('. $request->login, $request->user_id);
            return response()->json(['status'=>'ok', 'message'=>'changed name from ('.$oldName.') to ('.$request->name.'), login from ('.$oldLogin.') to ('. $request->login]);
        }else{
            return response()->json(['status'=>'error', 'message'=>'edit user is failed']);
        }

    }

    public function setUserPermit(Request $request, LogService $service){
        if ($request->allowed == 'true'){
            $set = new UserStorages();
            $set->storage_id = $request->storage_id;
            $set->user_id = $request->user_id;
            $res = $set->save();
        }else{
            $res =  UserStorages::where('storage_id','=', $request->storage_id)
                ->where('user_id', '=', $request->user_id)->delete();
            $service->newLog('setUserPermit', 'пользователь('.$request->user_id.'), был удален со склада('.$request->storage_id.')', null);
            $res =  UserStorages::
                      where('storage_id','=', $request->storage_id)
                    ->where('user_id', '=', $request->user_id)
                    ->delete();

            return response()->json(
                [
                    'status' => 'ok',
                    'message' => 'пользователь('.$request->user_id.'), был удален со склада('.$request->storage_id.')'
                ]);
        }

        if ($res){
            $service->newLog('setUserPermit', 'пользователь ('.$request->user_id.') получил доступ к складу('.$request->storage_id.')', null);
            return response()->json(
                [
                    'status' => 'ok',
                    'message' => 'пользователь ('.$request->user_id.') получил доступ к складу('.$request->storage_id.')'
                ]);
        }else{
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'при установлении права пользователя возникла ошибка'
                ]);
        }

    }
}
