<?php

namespace App\Http\Controllers\Api\District;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class StoreController extends Controller
{
    protected $districtServices;

    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
    }

    public function main(Request $request)
    {
        $params = $this->validation($request);
        $district = $this->districtServices->store($params);
        return response()->json($district, 200);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255'
        ]);
    }
}
