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


    public function get($conditions = [], $paginate = 10)
    {
        $query = $this->room->selectRaw("
            rooms.*,
            houses.address_detail as address"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id")
        ->join("areas", "houses.area_id", "=", "areas.id");
        $query = $this->addressFilter($query, $conditions);
        $query = $this->criteriaFilter($query, $conditions);
        return $query->with('criterias')->paginate($paginate);
    }

    /**
     * Filter rooms by address
     *
     * @param QueryBuilder $query
     * @param array $conditions
     *
     * @return QueryBuilder
     */
    private function addressFilter($query, array $conditions)
    {
        if (array_key_exists('lat', $conditions) && array_key_exists('lng', $conditions)) {
            //
        } elseif (array_key_exists('area', $conditions)) {
            return $query->where('areas.slug', $conditions['area']);
        } elseif (array_key_exists('district', $conditions)) {
            return $query->where('districts.slug', $conditions['district']);
        } elseif (array_key_exists('province', $conditions)) {
            return $query->where('provinces.slug', $conditions['province']);
        } elseif (array_key_exists('address', $conditions)) {
            return $query->where('houses.address_detail', 'like', "%".$conditions['address']."%");
        }
        return $query;
    }

    /**
     * Filter rooms by criterias
     *
     * @param QueryBuilder $query
     * @param array $conditions
     *
     * @return QueryBuilder
     */
    private function criteriaFilter($query, array $conditions)
    {
        if (array_key_exists('price', $conditions)) {
            $prices = config('const.ROOM_FILTER.PRICE')[$conditions['price']];
            $query = $query->whereBetween("rooms.price", [$prices['min'], $prices['max']]);
        }
        if (array_key_exists('acreage', $conditions)) {
            $acreages = config('const.ROOM_FILTER.ACREAGE')[$conditions['acreage']];
            $query = $query->whereBetween("rooms.acreage", [$acreages['min'], $acreages['max']]);
        }
        if (array_key_exists('criterias', $conditions)) {
            $query = $query->whereRaw("(
                select count(*)
                from room_criterias
                where room_id=rooms.id and criteria_id in (".$conditions['criterias'].") > 0
            )");
        }
        return $query;
    }

    /**
     * List rooms [for dashboard]
     *
     * @param integer $paginate
     *
     * @return mixed
     */
    public function list(array $conditions, $ownerId = null, $paginate = 10)
    {
        $query = $this->room->selectRaw(
            "rooms.*,
            houses.address_detail  as address,
            (select count(*) from users where users.room_id = rooms.id) as renters_count"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id")
        ->with('criterias');
        if (!is_null($ownerId)) {
            $query = $query->join("user_houses", "user_houses.house_id", "=", "houses.id")
                        ->where('user_houses.user_id', $ownerId);
        }
        $query = $this->listFilter($query, $conditions);
        return $query->paginate($paginate);
    }

    /**
     * Filter for list rooms
     *
     * @param QueryBuilder $query
     * @param array $conditions
     *
     * @return QueryBuilder
     */
    private function listFilter($query, array $conditions)
    {
        if (!is_null($conditions['house'])) {
            $query = $query->where("houses.id", $conditions['house']);
        } elseif (!is_null($conditions['area'])) {
            $query = $query->join("areas", "houses.area_id", "=", "areas.id")
                        ->where("areas.id", $conditions['area']);
        } elseif (!is_null($conditions['district'])) {
            $query = $query->where("districts.id", $conditions['district']);
        } elseif (!is_null($conditions['province'])) {
            $query = $query->where("provinces.id", $conditions['province']);
        }
        return $query;
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
            return $this->room->create($params);
        });
        $roomCriterias = [];
        foreach ($params['criterias'] as $criteria) {
            array_push($roomCriterias, ['room_id' => $room->id, 'criteria_id' => $criteria]);
        }
        DB::transaction(function () use ($roomCriterias) {
            $this->roomCriteria->insert($roomCriterias);
        });
        $room->criterias;
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
        $room = $this->room->findOrFail($id);
        $room = DB::transaction(function () use ($room, $params) {
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
        $room = $this->room->findOrFail($id);
        $oldImages = is_null($room->images) ? [] : json_decode($room->images);
        $images = array_merge($newImages, $oldImages);
        $images = DB::transaction(function () use ($room, $images) {
    		$room->update(['images' => json_encode($images)]);
            return $images;
        });
        return $images;
    }

    /**
     * @param integer $id
     */
    public function emptyRoom($id)
    {
        $room = $this->room->findOrFail($id);
        DB::transaction(function () use ($room) {
            $room->renters->update([
                'role' => config('const.USER.ROLE.NORMAL'),
                'room_id' => null
            ]);
        });
    }

    /**
     * Delete room by id
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $room = $this->room->findOrFail($id);
        DB::transaction(function () use ($room) {
            $room->delete();
        });
    }
}
