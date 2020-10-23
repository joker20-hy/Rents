<?php

namespace App\Http\Controllers\Api\district;

use App\Http\Controllers\Controller;
use App\Services\DistrictServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
    }

    public function main(Request $request)
    {
        $districts = $this->districtServices->list($request->provinceId);
        return response()->json($districts, 200);
    }
}
