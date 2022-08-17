<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    public $timestamps = false;

    protected $table = 'stock_balance';

    protected $fillable = [
        'goods_id',
        'storage_id',
        'price',
        'amount',
        'date_accepted',
    ];

    public function goods(){
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function storage(){
        return $this->belongsTo(Storages::class, 'storage_id');
    }
}
