<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storages extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'storages';
    protected $fillable = [
        'name',
        'type',
    ];
}
