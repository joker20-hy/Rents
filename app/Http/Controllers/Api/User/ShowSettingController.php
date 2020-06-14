<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SettingServices;

class ShowSettingController extends Controller
{
    protected $settingServices;

    public function __construct(SettingServices $settingServices)
    {
        $this->settingServices = $settingServices;
    }

    public function main(Request $request)
    {
        $setting = $this->settingServices->show($request->userId);
        return response()->json($setting, 200);
    }
}
