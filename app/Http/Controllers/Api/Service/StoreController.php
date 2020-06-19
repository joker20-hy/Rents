<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Services\ServiceServices;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $serviceServices;

    public function __construct(ServiceServices $serviceServices)
    {
        $this->serviceServices = $serviceServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $service = $this->serviceServices->store($params);
        return response()->json($service, 201);
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
