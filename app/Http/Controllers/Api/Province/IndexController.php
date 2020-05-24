<?php

namespace App\Http\Controllers\Api\Province;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProvinceServices;

class IndexController extends Controller
{
    protected $provinceServices;

    public function __construct(ProvinceServices $provinceServices)
    {
        $this->provinceServices = $provinceServices;
    }

    public function main(Request $request)
    {
        $provinces = $this->provinceServices->index($request->perpage);
        return response()->json($provinces, 200);
    }
}
