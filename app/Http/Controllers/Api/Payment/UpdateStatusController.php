<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentServices;

class UpdateStatusController extends Controller
{
    protected $paymentServices;

    public function __construct(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->paymentServices->update($request->id, $params);
        return response()->json([], 204);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'status' => 'required|boolean'
        ]);
    }
}
