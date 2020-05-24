<?php

namespace App\Http\Controllers\Api\Province;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProvinceServices;

class CreateController extends Controller
{
    protected $provinceServices;

    public function __construct(ProvinceServices $provinceServices)
    {
        $this->provinceServices = $provinceServices;
    }

    public function main(Request $request)
    {
        $validator = $this->validation($request);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
        $province = $this->provinceServices->create(['name' => $request->name]);
        return response()->json($province, 200);
    }

    public function validation(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|unique:provinces|max:255',
        ]);
    }
}
