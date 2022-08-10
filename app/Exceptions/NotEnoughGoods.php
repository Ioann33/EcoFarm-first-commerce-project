<?php

namespace App\Exceptions;

use Exception;

class NotEnoughGoods extends Exception
{
    public function resMess()
    {
        return 'не хватает продукции на складе';
    }
}
