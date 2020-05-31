<?php

namespace App\Http\Controllers\Api\District;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class DestroyController extends Controller
{
    protected $districtServices;

    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
    }

    public function main(Request $request)
    {
        $this->districtServices->destroy($request->id);
        return response()->json([], 204);
    }
}
