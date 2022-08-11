<?php

namespace App\Models\MyModel;

use App\Models\Money;

class MoneyTransfer
{
    /**
     * @param $amount
     * @param $price
     * @param $date
     * @param $storage_id
     * @param $description
     * @param $category
     * @param $param_id
     * @param $user_id
     * @return bool
     */
    static public function moneyTransfer($costProduct = null, $date = null, $storage_id = null, $description = null, $category = null, $param_id = null, $user_id = null): bool
    {
        $transaction = new Money();
        $transaction->date = $date;
        $transaction->storage_id = $storage_id;
        $transaction->size_pay = $costProduct;
        $transaction->description = $description;
        $transaction->category = $category;
        $transaction->param_id = $param_id;
        $transaction->user_id = $user_id;
        return $transaction->save();
    }

}
