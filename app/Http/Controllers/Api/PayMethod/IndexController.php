<?php

namespace App\Http\Controllers\Api\PayMethod;

use App\Http\Controllers\Controller;
use App\Services\PayMethodServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $payMethodServices;

    public function __construct(PayMethodServices $payMethodServices)
    {
        $this->payMethodServices = $payMethodServices;
    }

    public function main(Request $request)
    {
        $paymethods = $this->payMethodServices->index($request->owner_id);
        return response()->json($paymethods, 200);
    }
}
