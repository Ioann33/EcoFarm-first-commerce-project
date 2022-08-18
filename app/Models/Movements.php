<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movements extends Model
{
    use HasFactory;

    protected $table = 'movements';

    public $timestamps = false;

    public function goods(){
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function userAccepted(){
        return $this->belongsTo(User::class, 'user_id_accepted');
    }

    public function storageFrom(){
        return $this->belongsTo(Storages::class, 'storage_id_from');
    }

    public function storageTo(){
        return $this->belongsTo(Storages::class, 'storage_id_to');
    }
}
