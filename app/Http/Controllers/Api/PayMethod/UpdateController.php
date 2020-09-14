<?php

namespace App\Http\Controllers\Api\PayMethod;

use App\Http\Controllers\Controller;
use App\Services\PayMethodServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $payMethodServices;

    public function __construct(PayMethodServices $payMethodServices)
    {
        $this->payMethodServices = $payMethodServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->payMethodServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'owner_id' => 'required|integer|min:1',
            'name' => 'required|string',
            'account' => 'required|string',
            'note' => 'nullable|string'
        ]);
    }
}
