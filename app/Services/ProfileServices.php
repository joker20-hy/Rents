<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileServices
{
    private $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
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
        return $profile->update($params);
    }

    /**
     * Delete profile
     */
    public function destroy()
    {
        $profile = Auth::user()->profile;
        $profile->delete();
    }
}
