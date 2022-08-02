<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    protected $table = 'stock_balance';
    protected $fillable = [
        'goods_id',
        'price',
        'amount',
        'date_accepted',
    ];
}
