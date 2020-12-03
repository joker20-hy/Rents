<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\RentRoom;
use App\Models\Verification;
use App\Models\Application;

class UserRepository
{
    protected $user;
    protected $profile;
    protected $setting;
    protected $rentRoom;
    protected $verification;
    protected $application;

    public function __construct(
        User $user,
        Profile $profile,
        Setting $setting,
        RentRoom $rentRoom,
        Verification $verification,
        Application $application
    ) {
        $this->user = $user;
        $this->profile = $profile;
        $this->setting = $setting;
        $this->rentRoom = $rentRoom;
        $this->verification = $verification;
        $this->application = $application;
    }

    /**
     * Find users
     *
     * @param array $where
     * @param array $with
     *
     * @return Collection
     */
    public function find(array $where, array $with = [], $select = "*")
    {
        return $this->user->select($select)->where($where)->with($with)->get();
    }

    /**
     * Find first user
     *
     * @param array $where
     * @param array $with
     *
     * @return \App\Models\User
     */
    public function first(array $where, array $with = [], $select = "*")
    {
        return $this->user->select($select)->where($where)->with($with)->firstOrFail();
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
     * Find user record by email
     *
     * @param integer $email
     */
    public function findByEmail($email)
    {
        $user = $this->user->where('email', $email)->first();
        if (is_null($user)) {
            return abort(404, 'User not found');
        }
        return $user;
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
     * Create user and dependent relationship
     *
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function store(array $params)
    {
        $user = DB::transaction(function () use ($params) {
            $user = $this->user->create($params);
            $this->profile->create(['user_id' => $user->id]);
            $this->setting->create(['user_id' => $user->id]);
            return $user;
        });
        return $user;
    }

    /**
     * Update user record
     *
     * @param array $where
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function update(array $where, array $params)
    {
        $user = $this->user->where($where)->firstOrFail();
        return DB::transaction(function () use ($user, $params) {
            $user->update($params);
            return $user;
        });
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
    public function updateProfile($id, array $params)
    {
        return DB::transaction(function () use ($id, $params) {
            $this->profile->where('user_id', $id)->update($params);
            return $this->profile->where('user_id', $id)->firstOrFail();
        });
    }

    /**
     * Update setting record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Setting
     */
    public function updateSetting($id, array $params)
    {
        $user = $this->findById($id);
        $setting = $user->setting;
        return DB::transaction(function () use ($setting, $params) {
            $setting->update($params);
            return $setting;
        });
    }

    /**
     * Update or create verification record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Verification
     */
    public function updateVerification($id, $params)
    {
        $verification = $this->verification->updateOrCreate(['user_id' => $id], $params);
        return $verification;
    }

    /**
     * Delete user record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);
        DB::transaction(function () use ($user) {
            $user->profile->delete();
            $user->setting->delete();
            $user->delete();
        });
    }

    /**
     * Create application for owner
     *
     * @param array $params
     *
     * @return \App\Models\Application
     */
    public function createApplication(array $params)
    {
        return $this->application->create($params);
    }

     /**
     * Find first application for owner
     *
     * @param array $where
     *
     * @return \App\Models\Application
     */
    public function firstApplication(array $where)
    {
        return $this->application->where($where)
            ->with(['user' => function($query) {
                $query->with(['profile']);
            }])->orderBy('created_at')->firstOrFail();
    }

    public function listApplication($paginate = 10)
    {
        return $this->application->with(['user' => function($query) {
            $query->with(['profile']);
        }])->orderBy('created_at')
        ->orderBy('created_at')
        ->paginate($paginate);
    }

    public function updateApplication(array $where, array $params)
    {
        $this->application->where($where)->update($params);
        return $this->application->where($where)->firstOrFail();
    }
}
