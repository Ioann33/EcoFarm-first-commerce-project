<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStorages extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'users_storages';

    public function storage(){
        return $this->belongsTo(Storages::class, 'storage_id');
    }

}
