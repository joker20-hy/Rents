<?php

namespace App\Http\Controllers\Api\Payment\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentServices;

class StoreController extends Controller
{
	protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
    	$this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
    	$params = $this->validation($request);
    	$payment = $this->paymentServices->storeForRoom($params);
    	return response()->json($payment, 201);
    }

    public function validation(Request $request)
    {
    	return $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time' => 'required|date',
            'bill' => 'required|array'
    	]);
    }
}
