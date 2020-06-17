<?php

namespace App\Http\Controllers\Api\Suggest;

use App\Http\Controllers\Controller;
use App\Services\SuggestServices;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    protected $suggestServices;

    public function __construct(SuggestServices $suggestServices)
    {
        $this->suggestServices = $suggestServices;
    }

    public function main(Request $request)
    {
        $houses = $this->suggestServices->houses($request->keywords, [
            'province' => $request->province,
            'district' => $request->district,
            'area' => $request->area
        ]);
        return $houses;
    }
}
