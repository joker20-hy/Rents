<?php

namespace App\Http\Controllers\Api\PayMethod;

use App\Http\Controllers\Controller;
use App\Services\PayMethodServices;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    protected $payMethodServices;

    public function __construct(PayMethodServices $payMethodServices)
    {
        $this->payMethodServices = $payMethodServices;
    }

    public function main(Request $request)
    {
        $this->payMethodServices->destroy($request->id);
        return response()->json([], 200);
    }
}
