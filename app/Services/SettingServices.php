<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class SettingServices
{
    private $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Update user's setting
     * @param array $params
     */
    public function update($params)
    {
        $setting = Auth::user()->setting;
        return $setting->update($params);
    }
}
