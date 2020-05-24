<?php

namespace App\Http\Controllers\Api\District;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class CreateController extends Controller
{
    protected $districtServices;

    public function __construct(DistrictServices $districtServices)
    {
        $this->districtServices = $districtServices;
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
        $district = $this->districtServices->create($params);
        return response()->json($district, 200);
    }

    public function getParams(Request $request)
    {
        return $request->only('name', 'province_id');
    }

    public function validation(Array $params)
    {
        return Validator::make($params, [
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255'
        ]);
    }
}
