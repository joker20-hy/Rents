<?php

namespace App\Http\Controllers\Api\Payment\Room;

use App\Http\Controllers\Controller;
use App\Services\PaymentServices;
use Illuminate\Http\Request;

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
        $this->paymentServices->updateByRoom($request->id, $params);
        return response()->json([], 204);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'status' => 'required|boolean'
        ]);
    }
}
