<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\RoomCriteria;
use App\Models\RentRoom;
use App\Models\Room;
use App\Models\RoomPayMethod;

class RoomRepository
{
    protected $room;
    protected $rentRoom;
    protected $roomCriteria;
    protected $roomPayMethod;

    public function __construct(
        RoomCriteria $roomCriteria,
        Room $room,
        RentRoom $rentRoom,
        RoomPayMethod $roomPayMethod
    ) {
        $this->roomCriteria = $roomCriteria;
        $this->rentRoom = $rentRoom;
        $this->room = $room;
        $this->roomPayMethod = $roomPayMethod;
    }

    /**
     * Get an paginate rooms
     *
     * @param array $conditions
     * @param integer $paginate
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function index($conditions = [], $paginate = 10)
    {
        $query = $this->room->selectRaw("
            rooms.*,
            houses.address_detail as address"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id")
        ->join("areas", "houses.area_id", "=", "areas.id")
        ->where("rooms.status", "<>", config('const.ROOM_STATUS.rented'));
        $query = $this->addressFilter($query, $conditions);
        $query = $this->criteriaFilter($query, $conditions);
        $query = $this->sort($query, $conditions);
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
            $prices = (object)$conditions['price'];
            if (is_null($prices->max)) {
                $query = $query->where("rooms.price", ">", $prices->min);
            } else {
                $query = $query->whereBetween("rooms.price", [$prices->min, $prices->max]);
            }
        }
        if (array_key_exists('acreage', $conditions)) {
            $acreages = (object)$conditions['acreage'];
            if (is_null($acreages->max)) {
                $query = $query->where("rooms.acreage", ">", $acreages->min);
            } else {
                $query = $query->whereBetween("rooms.acreage", [$acreages->min, $acreages->max]);
            }
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
     * Sort rooms
     *
     * @param QueryBuilder $query
     * @param array $conditions
     *
     * @return QueryBuilder
     */
    private function sort($query, $conditions)
    {
        if (array_key_exists('sort', $conditions)) {
            $sort = (object)$conditions['sort'];
            $query = $query->orderBy("rooms.$sort->feild", $sort->order);
        } else {
            $query = $query->orderBy('rooms.avg_rate', 'desc');
        }
        return $query;
    }

    /**
     * List rooms [for dashboard]
     *
     * @param array $conditions
     * @param integer $ownerId
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
        ->with('criterias')
        ->with('paymethods');
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
     * @param array $select Select list
     *
     * @return \App\Models\Room
     */
    public function findById($id, $select = ['*'])
    {
        $room = $this->room->select($select)->where('id', $id)->first();
        if (is_null($room)) {
            return abort(404, 'Không tìm thấy phòng');
        }
        return $room;
    }

    /**
     * Find rent room contract
     *
     * @param integer $roomId
     *
     * @return \App\Models\RentRoom
     */
    public function getRent($roomId)
    {
        $rentRoom = $this->rentRoom->where('room_id', $roomId)->first();
        if (is_null($rentRoom)) {
            return abort(404, 'Rent room contract not found');
        }
        return $rentRoom;
    }

    /**
     * Find rent room contract
     *
     * @param integer $id
     *
     * @return \App\Models\RentRoom
     */
    public function getRentById($id)
    {
        return $this->rentRoom->findOrFail($id);
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
        $room = $this->room->create($params);
        $criterias = $params['criterias'];
        $room = DB::transaction(function () use ($room, $criterias) {
            $roomCriterias = [];
            foreach ($criterias as $criteria) {
                array_push($roomCriterias, [
                    'room_id' => $room->id,
                    'criteria_id' => $criteria,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
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
            if (array_key_exists('criterias', $params)) {
                $this->updateCriterias($room, $params['criterias']);
            }
            return $room;
        });
        return $room;
    }

    /**
     * Update room criterias
     *
     * @param \App\Models\Room $room
     * @param array $criteriaIds
     *
     * @return \App\Models\Room
     */
    private function updateCriterias(Room $room, array $criteriaIds)
    {
        $roomCriterias = [];
        $criterias = $room->criterias()->pluck('id');
        foreach ($criteriaIds as $id) {
            if (!$criterias->contains($id)) {
                array_push($roomCriterias, [
                    'room_id' => $room->id,
                    'criteria_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        DB::transaction(function ($roomCriterias, $room, $criteriaIds) {
            $this->roomCriteria->insert($roomCriterias);
            $this->roomCriteria->where('room_id', $room->id)->whereNotIn('criteria_id', $criteriaIds)->delete();
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

    public function updatePayMethods($id, array $paymethodIds)
    {
        $room = $this->findById($id);
        $paymethods = $room->paymethods->pluck('id');
        $params = [];
        foreach ($paymethodIds as $id) {
            if (!$paymethods->contains($id)) {
                array_push($params, [
                    'room_id' => $room->id,
                    'pay_method_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }   
        }
        DB::transaction(function () use ($params, $room, $paymethodIds) {
            $this->roomPayMethod->insert($params);
            $this->roomPayMethod->where('room_id', $room->id)->whereNotIn('pay_method_id', $paymethodIds)->delete();
        });
        
        return $room;
    }

    /**
     * Remove all renter from room
     * @param integer $id
     */
    public function emptyRoom($id)
    {
        $room = $this->room->findOrFail($id);
        DB::transaction(function () use ($room) {
            foreach ($room->renters as $renter) {
                $renter->update(['room_id' => null, 'role' => config('const.USER.ROLE.NORMAL')]);   
            }
            $room->update(['status' => false, 'renter_count' => 0]);
            $updateParams = ['to' => Carbon::now(), 'deleted_at' => Carbon::now()];
            $this->rentRoom->where('room_id', $room->id)->whereNull('to')->update($updateParams);
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
