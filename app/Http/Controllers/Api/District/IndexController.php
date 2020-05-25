<?php

namespace App\Http\Controllers\Api\District;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class IndexController extends Controller
{
    protected $districtServices;

    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
    }

    public function main(Request $request)
    {
        $districts = $this->districtServices->index($request->provinceId);
        return response()->json($districts, 200);
    }
}
