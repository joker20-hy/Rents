<?php

namespace App\Http\Controllers\Api\PayMethod;

use App\Http\Controllers\Controller;
use App\Services\PayMethodServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    protected $payMethodServices;

    public function __construct(PayMethodServices $payMethodServices)
    {
        $this->payMethodServices = $payMethodServices;
    }

    public function main(Request $request)
    {
        $payments = $this->payMethodServices->list($request->perpage);
        return response()->json($payments, 200);
    }
}
