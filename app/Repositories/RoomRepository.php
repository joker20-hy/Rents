<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\RoomCriteria;
use App\Models\Room;

class RoomRepository
{
    protected $room;
    protected $roomCriteria;

    public function __construct(RoomCriteria $roomCriteria, Room $room)
    {
        $this->roomCriteria = $roomCriteria;
        $this->room = $room;
    }

    /**
     * Find room record by id
     *
     * @param integer $id
     */
    public function findById($id)
    {
        return $this->room->findOrFail($id);
    }

    /**
     * Create room record
     *
     * @param array $params
     *
     * @return \App\Models\Room
     */
    public function store(array $params)
    {
        $room = DB::transaction(function () use ($params) {
            $room = $this->room->create($params);
            $roomCriterias = [];
            foreach ($params['criterias'] as $criteria) {
                array_push($roomCriterias, [
                    'room_id' => $room->id,
                    'criteria_id' => $criteria
                ]);
            }
            $this->roomCriteria->insert($roomCriterias);
            $room->criterias;
            return $room;
        });
        return $room;
    }

    /**
     * Update room record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Room
     */
    public function update($id, array $params)
    {
        $room = DB::transaction(function () use ($id, $params) {
            $room = $this->room->findOrFail($id);
            $room->update($params);
            return $room;
        });
        return $room;
    }

    /**
     * Update room record
     *
     * @param integer $id
     * @param array $newImages
     *
     * @return array
     */
    public function addImages($id, array $newImages)
    {
        $images = DB::transaction(function () use ($id, $newImages) {
            $room = $this->room->findOrFail($id);
            $oldImages = is_null($room->images) ? [] : json_decode($room->images);
            $images = array_merge($newImages, $oldImages);
    		$room->update(['images' => json_encode($images)]);
            return $images;
        });
        return $images;
    }
}
