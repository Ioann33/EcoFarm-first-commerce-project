<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    function getFinance(Request $request): array
    {
        return array('balance' => 432);
    }

    function doSalary(Request $request): array
    {
        //dd($request);
        return array('status' => 'sd');
    }

}
