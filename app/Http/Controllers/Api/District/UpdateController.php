<?php

namespace App\Http\Controllers\Api\District;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictServices;

class UpdateController extends Controller
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
        $this->districtServices->update($request->id, $params);
        return response()->json([], 204);
    }

    public function getParams(Request $request)
    {
        return $request->only('name', 'slug', 'province_id');
    }

    public function validation(Array $params)
    {
        return Validator::make($params, [
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'province_id' => 'nullable|exists:provinces,id'
        ]);
    }
}
