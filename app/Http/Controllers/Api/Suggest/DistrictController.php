<?php

namespace App\Http\Controllers\Api\Suggest;

use App\Http\Controllers\Controller;
use App\Services\SuggestServices;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    protected $suggestServices;

    public function __construct(SuggestServices $suggestServices)
    {
        $this->suggestServices = $suggestServices;
    }

    public function main(Request $request)
    {
        $districts = $this->suggestServices->districts($request->keywords, $request->province);
        return \response()->json($districts, 200);
    }
}
