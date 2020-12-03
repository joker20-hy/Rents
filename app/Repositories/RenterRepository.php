<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\RentRoom;
use App\Models\RoomPayment;
use App\Models\RoommateWanted;

class RenterRepository
{
    protected $rentRoom;
    protected $roomPayment;
    protected $roommateWanted;

    public function __construct(
        RentRoom $rentRoom,
        RoomPayment $roomPayment,
        RoommateWanted $roommateWanted
    ) {
        $this->rentRoom = $rentRoom;
        $this->roomPayment = $roomPayment;
        $this->roommateWanted = $roommateWanted;
    }

    /**
     * Create rent room contract of user
     *
     * @param integer $userId
     * @param integer $roomId
     *
     * @return \App\Models\RentRoom
     */
    public function createRentRoomContract($userId, $roomId)
    {
        return $this->rentRoom->firstOrCreate(
            ['renter_id' => $userId, 'room_id' => $roomId],
            ['renter_id' => $userId, 'room_id' => $roomId, 'from' => Carbon::now(), 'to' => null]
        );
    }

    /**
     * Find rent room contract of user
     *
     * @param integer $userId
     * @param integer|null $roomId
     * @param bool $findOrFail
     *
     * @return \App\Models\RentRoom
     */
    public function findRentRoomContract($roomId, $userId = null, $findOrFail = true)
    {
        $query = $this->rentRoom->where('room_id', $roomId);
        $query = is_null($userId) ? $query : $query->where('renter_id', $userId);
        return $findOrFail ? $query->firstOrFail() : $query->find();
    }

    /**
     * Delete RentRoom record
     *
     * @param integer $roomId
     * @param integer|null $userId
     */
    public function destroyRentRoomContract($roomId, $userId = null)
    {
        $query = $this->rentRoom->where('room_id', $roomId);
        if (!is_null($userId)) {
            $query = $query->where('renter_id', $userId);
        }
        return $query->update(['to' => Carbon::now(), 'deleted_at' => Carbon::now()]);
    }

    /**
     * Create RoomPayment record
     *
     * @param array $params
     *
     * @return \App\Models\RoomPayment
     */
    public function createRoomPayment(array $params)
    {
        return $this->roomPayment->create($params);
    }

    public function findRoommateWanted($roomId)
    {
        return $this->roommateWanted->where(['room_id' => $roomId])->firstOrFail();
    }

    /**
     * Create roommate wanted record
     *
     * @param array $params
     *
     * @return \App\Models\RoommateWanted
     */
    public function createRoommateWanted(array $params)
    {
        return $this->roommateWanted->create($params);
    }

    public function updateRoommateWanted($roomId, array $params)
    {
        $this->roommateWanted->where(['room_id' => $roomId])->update($params);
    }

    /**
     * Delete roommate wanted record
     *
     * @param integer $roomId
     */
    public function deleteRoommateWanted($roomId)
    {
        $this->roommateWanted->where(['room_id' => $roomId])->delete();
    }
}
