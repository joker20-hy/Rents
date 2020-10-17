<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\RentRoom;
use App\Models\RoomPayment;

class RenterRepository
{
    protected $rentRoom;
    protected $roomPayment;

    public function __construct(RentRoom $rentRoom, RoomPayment $roomPayment)
    {
        $this->rentRoom = $rentRoom;
        $this->roomPayment = $roomPayment;
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
}
