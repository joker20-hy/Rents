<?php

namespace App\Http\Controllers\Api\Province;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProvinceServices;

class DestroyController extends Controller
{
    protected $provinceServices;

    public function __construct(ProvinceServices $provinceServices)
    {
        $this->provinceServices = $provinceServices;
    }

    public function main(Request $request)
    {
        $this->provinceServices->destroy($request->id);
        return response()->json([], 204);
    }
}
