<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'readygoods_ingredients';
    public $timestamps = false;
    protected $primaryKey = false;

    protected $fillable = [
        'readygoods_id',
        'ingredients_id'
    ];

    public $incrementing = false;
    public function readyGoods(){
        return $this->belongsTo(Goods::class, 'readygoods_id');
    }

    public function ingredients(){
        return $this->belongsTo(Goods::class, 'ingredients_id');
    }
}
