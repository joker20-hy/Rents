<?php

namespace App\Http\Controllers\Api\Suggest;

use App\Http\Controllers\Controller;
use App\Services\SuggestServices;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    protected $suggestServices;

    public function __construct(SuggestServices $suggestServices)
    {
        $this->suggestServices = $suggestServices;
    }

    public function main(Request $request)
    {
        $provinces = $this->suggestServices->provinces($request->keywords, $request->limit);
        return \response()->json($provinces, 200);
    }
}
