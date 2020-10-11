<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\RentRoom;
use App\Models\Verification;

class UserRepository
{
    protected $user;
    protected $profile;
    protected $setting;
    protected $rentRoom;
    protected $verification;

    public function __construct(
        User $user,
        Profile $profile,
        Setting $setting,
        RentRoom $rentRoom,
        Verification $verification
    ) {
        $this->user = $user;
        $this->profile = $profile;
        $this->setting = $setting;
        $this->rentRoom = $rentRoom;
        $this->verification = $verification;
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
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function update($id, array $params)
    {
        $user = $this->user->findOrFail($id);
        $user = DB::transaction(function () use ($user, $params) {
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
    public function updateProfile($id, array $params)
    {
        $user = $this->findById($id);
        $profile = $user->profile;
        return DB::transaction(function () use ($profile, $params) {
            $profile->update($params);
            return $profile;
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
     * User rent room
     *
     * @param integer $id
     * @param integer $roomId
     *
     * @return \App\Models\User
     */
    public function rentRoom($id, $roomId)
    {
        $user = $this->user->findOrFail($id);
        $user = DB::transaction(function () use ($user, $roomId) {
            $user = $this->update($user->id, ['role' => config('const.USER.ROLE.RENTER'), 'room_id' => $roomId]);
            $this->createContract($user->id, $roomId);  
            $updateParams = ['renter_count' => $user->room->renter_count + 1];
            if ($user->room->status!=config('const.ROOM_STATUS.rented')) {
                $updateParams['status'] = config('const.ROOM_STATUS.rented');
            }
            $user->room->update($updateParams);
            return $user;
        });
        return $user;
    }

    /**
     * Create contract between user and room
     *
     * @param integer $id
     * @param integer $roomId
     *
     * @return \App\Models\RentRoom
     */
    public function createContract($id, $roomId)
    {
        $rentRoom = $this->rentRoom->where('renter_id', $id)
                ->where('room_id', $roomId)
                ->whereNotNull('to')
                ->first();
        if (is_null($rentRoom)) {
            $rentRoom = $this->rentRoom->create([
                'renter_id' => $id,
                'room_id' => $roomId,
                'from' => Carbon::now(),
                'to' => null
            ]);
        }
        return $rentRoom;
    }

    /**
     * User leave room
     *
     * @param integer $id
     *
     * @return \App\Models\User
     */
    public function leaveRoom($id)
    {
        $user = $this->user->findOrFail($id);
        $rentRoom = $this->findContract($user);
        return DB::transaction(function () use ($user, $rentRoom) {
            $room = $user->room;
            $rentRoom->update(['to' => Carbon::now(), 'deleted_at' => Carbon::now()]);
            $user = $this->update($user->id, ['role' => config('const.USER.ROLE.RENTER'), 'room_id' => null]);
            $roomUpdate = ['renter_count' => $room->renter_count - 1];
            if ($room->renter_count == 1) {
                $roomUpdate['status'] = config('const.ROOM_STATUS.waiting');
            }
            $room->update($roomUpdate);
            return $user;
        });
    }

    public function findContract(User $user)
    {
        $rentRoom = $this->rentRoom->where('room_id', $user->room_id)
                        ->where('renter_id', $user->id)
                        ->whereNotNull('to')
                        ->first();
        if (is_null($rentRoom)) {
            return abort(404, 'Rent contract not found');
        }
        return $rentRoom;
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
}
