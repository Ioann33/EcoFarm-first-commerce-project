<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movements extends Model
{
    use HasFactory;

    protected $table = 'movements';

    public function goods(){
        return $this->belongsTo(Goods::class, 'goods_id');
    }
}
