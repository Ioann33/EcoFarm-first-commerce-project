<?php

namespace App\Exceptions;

use Exception;

class NotEnoughGoods extends Exception
{
    public function resMess()
    {
        return 'not enough goods in stock';
    }
}
