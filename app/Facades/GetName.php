<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GetName extends Facade
{
    /**
     * @see \App\Services\GetNameService
     */
    protected static function getFacadeAccessor()
    {
        return 'getName';
    }
}
