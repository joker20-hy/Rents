<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\RoomCriteria;
use App\Repositories\RoomRepository;

class RoomServices
{
    private $folder;
    protected $room;
    protected $imageServices;
    protected $roomCriteria;
    protected $roomRepository;

    public function __construct(Room $room, RoomCriteria $roomCriteria, ImageServices $imageServices, RoomRepository $roomRepository)
    {
        $this->folder = config('const.FOLDER.ROOM');
        $this->room = $room;
        $this->roomCriteria = $roomCriteria;
        $this->imageServices = $imageServices;
        $this->roomRepository = $roomRepository;
    }

    /**
     * List rooms
     *
     * @param array $params
     * @param integer $paginate
     */
    public function index($conditions = [], $paginate = 10)
    {
        $rooms = $this->room->selectRaw("
            rooms.*,
            houses.address_detail as address"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id")
        ->join("areas", "houses.area_id", "=", "areas.id");
        $rooms = $this->search($rooms, $conditions);
        $rooms = $this->filter($rooms, $conditions);
        $rooms = $rooms->paginate($paginate);
        foreach($rooms as $room) {
            $room->criterias;
        }
        return $rooms;
    }

    private function search($query, $conditions)
    {
        if (isset($conditions['lat']) && isset($conditions['lng'])) {
            //
        } elseif (isset($conditions['area'])) {
            return $query->where('areas.slug', $conditions['area']);
        } elseif (isset($conditions['district'])) {
            return $query->where('districts.slug', $conditions['district']);
        } elseif (isset($conditions['province'])) {
            return $query->where('provinces.slug', $conditions['province']);
        } elseif (isset($conditions['address'])) {
            return $query->where('houses.address_detail', 'like', "%".$conditions['address']."%");
        }
        return $query;
    }

    private function filter($query, $conditions)
    {
        if (array_key_exists('price', $conditions)) {
            $price_condition = config('const.ROOM_FILTER.PRICE')[$conditions['price']];
            $query = $query->whereBetween("rooms.price", [$price_condition['min'], $price_condition['max']]);
        }
        if (array_key_exists('acreage', $conditions)) {
            $acreage_condition = config('const.ROOM_FILTER.ACREAGE')[$conditions['acreage']];
            $query = $query->whereBetween("rooms.acreage", [$acreage_condition['min'], $acreage_condition['max']]);
        }
        if (array_key_exists('infras', $conditions)) {
            $query = $query->whereRaw("(
                select
                    count(*)
                from room_criterias
                where
                    room_id=rooms.id and criteria_id in (".$conditions['infras'].") > 0
            )");
        }
        return $query;
    }

    private function sort($query, $conditions)
    {
        //
    }

    /**
     * List rooms
     *
     * @param array $conditions
     * @param integer $paginate
     */
    public function list($conditions = [], $paginate = 10)
    {
        $authUser = Auth::user();
        $rooms = $this->room->selectRaw(
            "rooms.*,
            houses.address_detail  as address,
            (select count(*) from users where room_id=rooms.id) as renters_count"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id");
        if ($authUser->role==config('USER.ROLE.OWNER')) {
            $rooms = $rooms->join("user_houses", "user_houses.house_id", "=", "houses.id")
                        ->where('user_houses.user_id', $authUser->id);
        }
        $rooms = $this->listFilter($rooms, $conditions);
        return $rooms->paginate($paginate);
    }

    /**
     * Filter for list rooms
     *
     * @param mixed $query
     * @param array $conditions []
     */
    private function listFilter($query, $conditions)
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
     * Store new room
     *
     * @param array $params
     *
     * @return \App\Models\Room
     */
    public function store(array $params)
    {
        $images = $this->imageServices->store($params['images']);
        $params['images'] = json_encode($images);
        $params['description'] = utf8_encode($params['description']);
        return $this->roomRepository->store($params);
    }

    /**
     * @param integer $id
     */
    public function show($id)
    {
        $room = $this->roomRepository->findById($id);
        $room->criterias;
        $room->house;
        $room->renters_count = $room->renters->count();
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
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to update room");
        }
        if (isset($params['description'])) {
            $params['description'] = utf8_encode($params['description']);
        }
        return $this->roomRepository->update($id, $params);
    }

        /**
     * Upload new room's images
     *
     * @param integer $id
     * @param array $images array of image files
     *
     * @return array
     */
    public function uploadImages($id, array $images)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to add room's images");
        }
        $newImages = $this->imageServices->store($images, $this->folder);
        $urls = $this->roomRepository->addImages($id, $newImages);
        return $urls;
    }

     /**
     * Update room's images
     *
     * @param integer $id
     * @param array $images array of images url
     */
    public function updateImages($id, array $images)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to update room's images");
        }
        $this->roomRepository->update($id, ['images' => json_encode($images)]);
    }

    /**
     * Delete room
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $room = $this->room->findOrFail($id);
        if ($this->permission($room->house_id)) {
            $room->delete();
        } else {
            return abort(403, "You have no permission to delete room");
        }
    }

    public function getServices($id)
    {
        if (!$this->permission($id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        $room = $this->roomRepository->findById($id);
        $roomServices = $room->house->houseServices;
        foreach($roomServices as $roomService) {
            $roomService->service;
        }
        return [
            'room' => $room,
            'room_services' => $roomServices
        ];
    }

    /**
     * Check if current user has permission
     *
     * @param integer $id
     *
     * @return boolean
     */
    private function permission($id)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.ADMIN')) {
            return true;
        } elseif ($authUser->role==config('const.USER.ROLE.OWNER')) {
            $room = $this->roomRepository->findById($id);
            $houseIds = $authUser->houses->pluck('id');
            return $houseIds->contains($room->house_id);
        }
        return false;
    }
}

