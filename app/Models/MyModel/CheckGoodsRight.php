<?php

namespace App\Models\MyModel;

use App\Models\StorageGoods;

class CheckGoodsRight
{
    /**
     * @param int $goods_id
     * @return bool
     */
    public static function check(int $goods_id, int $storage_id)
    {
        $permit = StorageGoods::where('goods_id', '=', $goods_id)->where('storage_id', '=', $storage_id)->get();
        if (count($permit)>0){
            return false;
        }
        return true;
    }
}
