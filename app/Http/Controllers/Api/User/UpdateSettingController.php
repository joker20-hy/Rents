<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SettingServices;

class UpdateSettingController extends Controller
{
    protected $settingServices;

    public function __construct(SettingServices $settingServices)
    {
        $this->settingServices = $settingServices;
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
        $this->settingServices->update($request->userId, $params);
        return response()->json([], 204);
    }

    public function getParams(Request $request)
    {
        return $request->only('verification_2_step');
    }

    public function validation(array $params)
    {
        return Validator::make($params, [
            'verification_2_step' => 'required|boolean'
        ]);
    }
}
