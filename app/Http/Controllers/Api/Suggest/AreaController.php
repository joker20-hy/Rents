<?php

namespace App\Http\Controllers\Api\Suggest;

use App\Http\Controllers\Controller;
use App\Services\SuggestServices;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    protected $suggestServices;

    public function __construct(SuggestServices $suggestServices)
    {
        $this->suggestServices = $suggestServices;
    }

    public function main(Request $request)
    {
        $areas = $this->suggestServices->areas(
        	$request->keywords,
        	$request->province,
        	$request->district
        );
        return \response()->json($areas, 200);
    }
}
