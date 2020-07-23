<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    protected $user;
    protected $profile;
    protected $setting;

    public function __construct(User $user, Profile $profile, Setting $setting)
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->setting = $setting;
    }

    /**
     * Find user record by id
     *
     * @param integer $id
     */
    public function findById($id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * List user and paginate
     *
     * @param integer $paginate
     *
     * @return ([\App\Models\User])
     */
    public function list($paginate)
    {
        return $this->user->with('profile')->with('setting')->paginate($paginate);
    }

    /**
     * Update user record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function update($id, array $params)
    {
        $user = DB::transaction(function () use ($id, $params) {
            $user = $this->user->findOrFail($id);
            $user->update($params);
            return $user;
        });
        return $user;
    }

    /**
     * Find profile by user id
     *
     * @param integer $id
     *
     * @return \App\Models\Profile
     */
    public function showProfile($id)
    {
        $profile = $this->profile->where('user_id', $id)->first();
        $profile->avatar();
        return $profile;
    }

    /**
     * Update profile record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Profile
     */
    public function updateProfile($id, $params)
    {
        $profile = DB::transaction(function () use ($id, $params) {
            $this->profile->where('user_id', $id)->update($params);
            $profile = $this->profile->where('user_id', $id)->first();
            return $profile;
        });
        return $profile;
    }

    /**
     * Update setting record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Setting
     */
    public function updateSetting($id, $params)
    {
        $setting = DB::transaction(function () use ($id, $params) {
            $this->setting->where('user_id', $id)->update($params);
            $setting = $this->setting->where('user_id', $id)->first();
            return $setting;
        });
        return $setting;
    }

    /**
     * Delete user record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $user = $this->user->findOrFail($id);
            $user->profile->delete();
            $user->setting->delete();
            $user->delete();
        });
    }
}
