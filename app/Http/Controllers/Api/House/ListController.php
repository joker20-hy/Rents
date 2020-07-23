<?php

namespace App\Http\Controllers\Api\house;

use App\Http\Controllers\Controller;
use App\Services\HouseServices;
use Illuminate\Http\Request;

class ListController extends Controller
{
    protected $houseServices;

    public function __construct(HouseServices $houseServices)
    {
        $this->houseServices = $houseServices;
    }

    public function main(Request $request)
    {
        $conditions = $this->validation($request);
        $houses = $this->houseServices->list($conditions);
        return response()->json($houses, 200);
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'province' => 'nullable|integer',
            'district' => 'nullable|integer',
            'area' => 'nullable|integer'
        ]);
    }
}
