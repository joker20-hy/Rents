<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentServices;

class ListController extends Controller
{
    protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
        $payments = $this->paymentServices->list($request->room_id);
        return response()->json($payments, 200);
    }
}
