<?php

namespace App\Services;

use App\Models\Goods;
use App\Models\Storages;
use App\Models\User;

class GetNameService
{

    /**
     * @param int|null $user_id
     * @return mixed
     */
    public function user(int $user_id = null){
        if (isset($user_id)){
            $user = User::findOrFail($user_id);
            return $user->name;
        }
        return null;
    }

    /**
     * @param int|null $storage_id
     * @return mixed
     */
    public function storage(int $storage_id = null){
        if (isset($storage_id)){
            $storage = Storages::findOrFail($storage_id);
            return $storage->name;
        }
        return null;
    }

    /**
     * @param int|null $goods_id
     * @return mixed
     */
    public function goods(int $goods_id = null){
        if (isset($goods_id)){
            $goods = Goods::findOrFail($goods_id);
            return $goods->name;
        }
        return null;
    }
}
