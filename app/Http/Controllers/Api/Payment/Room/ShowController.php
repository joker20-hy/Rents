<?php

namespace App\Http\Controllers\Api\Payment\Room;

use App\Http\Controllers\Controller;
use App\Services\PaymentServices;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
    	$this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
        $payment = $this->paymentServices->showByRoom($request->id);
        return response()->json($payment, 200);
    }
}
