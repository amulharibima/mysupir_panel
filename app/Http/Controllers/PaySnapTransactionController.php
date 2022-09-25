<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaySnapTransactionController extends Controller
{
    public function __invoke($snapToken)
    {
        return view('pay_transaction', compact('snapToken'));
    }
}
