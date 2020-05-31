<?php

namespace App\Http\Controllers\Api\Province;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProvinceServices;

class UpdateController extends Controller
{
    protected $provinceServices;

    public function __construct(ProvinceServices $provinceServices)
    {
        $this->provinceServices = $provinceServices;
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
        $this->provinceServices->update($request->id, $params);
        return response()->json([], 200);
    }

    public function getParams(Request $request)
    {
        return $request->only('name', 'slug');
    }

    public function validation(Array $params)
    {
        return Validator::make($params, [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);
    }
}
