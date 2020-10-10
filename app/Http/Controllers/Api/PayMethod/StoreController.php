<?php

namespace App\Http\Controllers\Api\PayMethod;

use App\Http\Controllers\Controller;
use App\Services\PayMethodServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $payMethodServices;

    public function __construct(PayMethodServices $payMethodServices)
    {
        $this->payMethodServices = $payMethodServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $payMethod = $this->payMethodServices->store($params);
        return response()->json($payMethod, 201);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'owner_id' => 'nullable|integer|min:1',
            'name' => 'required|string',
            'account' => 'required|string',
            'note' => 'nullable|string'
        ]);
    }
}
