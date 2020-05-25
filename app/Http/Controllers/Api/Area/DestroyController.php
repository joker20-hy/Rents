<?php

namespace App\Http\Controllers\Api\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaServices;

class DestroyController extends Controller
{
    protected $areaServices;

    public function __construct(AreaServices $areaServices)
    {
        $this->areaServices = $areaServices;
    }

    public function main(Request $request)
    {
        $this->areaServices->destroy($request->id);
        return response()->json([], 204);
    }
}
