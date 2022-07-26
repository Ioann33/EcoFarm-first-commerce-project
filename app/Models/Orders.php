<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id_created',
        'date_created',
        'storage_id_from',
        'storage_id_to',
        'goods_id',
        'amount',
        'order_main',
        'user_id_handler',
        'date_status',
        'status',
    ];

    public function goods(){
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function storageFrom(){
        return $this->belongsTo(Storages::class, 'storage_id_from');
    }

    public function storageTo(){
        return $this->belongsTo(Storages::class, 'storage_id_to');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function handler(){
        return $this->belongsTo(User::class, 'user_id_handler');
    }

}
