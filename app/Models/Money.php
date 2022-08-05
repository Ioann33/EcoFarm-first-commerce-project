<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    use HasFactory;

    protected $table = 'money';

    public $timestamps = false;

    protected $fillable = [
        'date',
        'storage_id',
        'size_pay',
        'description',
        'category',
        'param_id',
    ];
}
