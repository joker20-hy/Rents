<?php

namespace App\Http\Controllers\Api\Payment\Room;

use App\Http\Controllers\Controller;
use App\Services\PaymentServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
        $payments = $this->paymentServices->listByRoom($request->room_id);
        return response()->json($payments, 200);
    }
}
