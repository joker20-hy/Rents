<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserServices
{
    protected $user;
    protected $profileServices;

    public function __construct(User $user, ProfileServices $profileServices)
    {
        $this->user = $user;
        $this->profileServices = $profileServices;
    }

    public function index($paginate = 10)
    {
        return $this->user->with('profile')
                    ->with('setting')
                    ->paginate($paginate);
    }

    public function show($id=null)
    {
        $authUser = Auth::user();
        if (is_null($id)) {
            $authUser->profile;
            return $authUser;
        } elseif ($authUser->role != config('const.USER.ROLE.ADMIN') && $authUser->id != $id) {
            return abort(403, 'You have no permission');
        } else {
            $user = $this->user->findOrFail($id);
            $user->profile;
            return $user;
        }        
    }

    /**
     * Update verify status of user
     *
     * @param integer $id
     * @param integer $verifyStatus
     *
     * @return \App\Models\User
     */
    public function updateVerifyStatus($id, $verifyStatus)
    {
        $user = $this->user->findOrFail($id);
        $user->update([
            'verify' => $verifyStatus
        ]);
        return $user;
    }

    /**
     * Get user profile
     *
     * @param integer $id
     *
     * @return \App\Models\Profile
     */
    public function showProfile($id)
    {
        if (Auth::user()->role != config('const.USER.ROLE.ADMIN')) {
            return Auth::user()->profile;
        }
        $user = $this->user->findOrfail($id);
        $profile = $user->profile;
        $profile->avatar();
        return $profile;
    }

    public function updateProfile($id, $params)
    {
        $authUser = Auth::user();
        if ($authUser->role != config('const.USER.ROLE.ADMIN')) {
            if ($authUser->id!=$id) {
                return abort(403, 'You do not have permission to update this profile');
            }
            $authUser->profile->update($params);
            return $authUser->profile;
        }
        $user = $this->user->findOrFail($id);
        $user->profile->update($params);
        return $user->profile;
    }

    public function destroy($id)
    {
        if (Auth::user()->role==config('const.USER.ROLE.ADMIN')) {
            $user = $this->user->findOrFail($id);
            DB::transaction(function () use ($user) {
                $user->delete();
                $user->profile->delete();
            });
        } else {
            return abort(403, "You have no permission to do this action");
        }
    }
}
