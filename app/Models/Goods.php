<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;

    protected $table = 'goods';

    protected $fillable = [

    ];

    public function movements(){
        return $this->hasMany(Movements::class, 'goods_id');
    }
}
