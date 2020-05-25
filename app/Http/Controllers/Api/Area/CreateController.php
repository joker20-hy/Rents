<?php

namespace App\Http\Controllers\Api\Area;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaServices;

class CreateController extends Controller
{
    protected $areaServices;

    public function __construct(AreaServices $areaServices)
    {
        $this->areaServices = $areaServices;
    }

    public function main(Request $request)
    {
        $params = $this->getParams($request);
        $validator = $this->validation($params);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
        $area = $this->areaServices->create($params);
        return response()->json($area, 200);
    }

    public function getParams(Request $request)
    {
        return $request->only('province_id', 'district_id', 'name');
    }

    public function validation(Array $params)
    {
        return Validator::make($params, [
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255'
        ]);
    }
}
