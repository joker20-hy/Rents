<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Services\ServiceServices;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $serviceServices;

    public function __construct(ServiceServices $serviceServices)
    {
        $this->serviceServices = $serviceServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $this->serviceServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function validation(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|integer'
        ]);
    }
}
