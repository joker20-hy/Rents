<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentServices;

class ShowController extends Controller
{
    protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
        $payment = $this->paymentServices->show($request->id);
        return response()->json($payment, 200);
    }
}
