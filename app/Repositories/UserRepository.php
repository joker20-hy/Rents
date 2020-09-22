<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\RentRoom;

class UserRepository
{
    protected $user;
    protected $profile;
    protected $setting;
    protected $rentRoom;

    public function __construct(User $user, Profile $profile, Setting $setting, RentRoom $rentRoom)
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->setting = $setting;
        $this->rentRoom = $rentRoom;
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
        DB::transaction(function () use ($id, $params) {
            $this->profile->where('user_id', $id)->update($params);
        });
        $profile = DB::transaction(function () use ($id) {
            return $this->profile->where('user_id', $id)->first();
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
    public function updateSetting($id, array $params)
    {
        DB::transaction(function () use ($id, $params) {
            $this->setting->where('user_id', $id)->update($params);
        });
        $setting = DB::transaction(function () use ($id) {
            return $this->setting->where('user_id', $id)->first();
        });
        return $setting;
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
            $user = $this->update($user->id, [
                'role' => config('const.USER.ROLE.RENTER'),
                'room_id' => $roomId
            ]);
            $this->createContract($user->id, $roomId);
            if ($user->room->status!=config('const.ROOM_STATUS.rented')) {
                $user->room->update(['status' => config('const.ROOM_STATUS.rented')]);
            }
            return $user;
        });
        return $user;
    }

    /**
     * Create contract between user and room
     *
     * @param integer $userId
     * @param integer $roomId
     *
     * @return \App\Models\RentRoom
     */
    public function createContract($userId, $roomId)
    {
        $rentRoom = $this->rentRoom->where('renter_id', $userId)
                ->where('room_id', $roomId)
                ->whereNotNull('to')
                ->first();
        if (is_null($rentRoom)) {
            $rentRoom = $this->rentRoom->create([
                'renter_id' => $userId,
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
        $user = DB::transaction(function () use ($user, $rentRoom) {
            $room = $user->room;
            $rentRoom->update(['to' => Carbon::now(), 'deleted_at' => Carbon::now()]);
            $user = $this->update($user->id, [
                'role' => config('const.USER.ROLE.RENTER'),
                'room_id' => null
            ]);
            if (count($room->renters) == 0) {
                $room->update(['status', config('const.ROOM_STATUS.waiting')]);
            }
        });
        return $user;
    }

    public function findContract(User $user)
    {
        $rentRoom = $this->rentRoom->where('room_id', $user->room_id)
                        ->where('renter_id', $user->id)
                        ->whereNotNull('to')
                        ->first();
        if (is_null($rentRoom)) {
            return abort(404, 'rent contract not found');
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
