<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class SettingServices
{
    protected $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function show($userId)
    {
        $setting = $this->find($userId);
        return $setting;
    }

    /**
     * Update user's setting
     * @param array $params
     */
    public function update($userId, $params)
    {
        $setting = $this->find($userId);
        $setting->update($params);
        return $setting;
    }

    public function find($userId)
    {
        $authUser = Auth::user();
        if ($authUser->role!=config('const.USER.ROLE.ADMIN')) {
            if ($authUser->id != $userId) {
                return abort(403, 'You have no permission');
            }
            $setting = $authUser->setting;
        } else {
            $setting = $this->setting->where('user_id', $userId)->first();
        }
        if (is_null($setting)) {
            return abort(404, 'Not found');
        }
        return $setting;
    }
}
