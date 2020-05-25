<?php

namespace App\Http\Controllers\Api\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaServices;

class IndexController extends Controller
{
    protected $areaServices;

    public function __construct(AreaServices $areaServices)
    {
        $this->areaServices = $areaServices;
    }

    public function main(Request $request)
    {
        $areas = $this->areaServices->index($request->type, $request->id);
        return response()->json($areas, 200);
    }
}
