<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileServices
{
    private const FOLDER = 'avatar';
    protected $profile;
    protected $imageServices;

    public function __construct(Profile $profile, ImageServices $imageServices)
    {
        $this->profile = $profile;
        $this->imageServices = $imageServices;
    }

    public function list()
    {
        $select = [
            'firstname',
            'lastname',
            'phone',
            'address',
            'date_of_birth'
        ];
        return $this->profile->select($select)->get();
    }

    /**
     * Get profile of auth user
     *
     * @return \App\Models\Profile
     */
    public function show()
    {
        return Auth::user()->profile;
    }

    /**
     * Create profile record
     * @param array $params {
     *  'firstname',
     *  'lastname',
     *  'phone',
     *  'address',
     *  'date_of_birth'
     * }
     *
     * @return \App\Models\Profile
     */
    public function store($params)
    {
        return $this->profile->create($params);
    }

    /**
     * Update profile record
     * @param integer $id
     * @param array $params {
     *  'firstname',
     *  'lastname',
     *  'phone',
     *  'address',
     *  'date_of_birth'
     * }
     *
     * @return \App\Models\Profile
     */
    public function update($params)
    {
        $profile = Auth::user()->profile;
        $profile->update($params);
        return $profile;
    }

    /**
     * Update profile image
     *
     * @param file $image
     *
     * @return string
     */
    public function updateAvatar($id, $image)
    {
        if ($this->permissible($id)) {
            $profile = $this->profile->where('user_id', $id)->first();
            if (is_null($profile)) {
                return abort(404, 'User not found');
            }
            $url = $this->imageServices->store([$image], self::FOLDER);
            $profile->update(['image' => $url]);
            return $url;
        }
    }

    /**
     * Check if user has permission
     *
     * @param integer $userId
     *
     * @return boolean
     */
    private function permissible($userId)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('USER.ROLE.ADMIN') || $authUser->id==$userId) {
            return true;
        }
        return false;
    }
}
