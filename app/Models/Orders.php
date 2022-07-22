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

}
