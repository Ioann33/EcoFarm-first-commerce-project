<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageGoods extends Model
{
    use HasFactory;
    protected $table = 'storage_goods';

    public $timestamps = false;

    public function goods(){
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function storage(){
        return $this->belongsTo(Storages::class, 'storage_id');
    }
}
