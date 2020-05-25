<?php

namespace App\Http\Controllers\Api\Area;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaServices;

class UpdateController extends Controller
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
        $area = $this->areaServices->update($request->id, $params);
        return response()->json($area, 200);
    }

    public function getParams(Request $request)
    {
        return $request->only('province_id', 'district_id', 'name', 'slug');
    }

    public function validation(Array $params)
    {
        return Validator::make($params, [
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255'
        ]);
    }
}
