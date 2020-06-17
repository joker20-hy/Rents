<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Room;

class RoomServices
{
    private $folder;
    protected $room;
    protected $imageServices;

    public function __construct(Room $room, ImageServices $imageServices)
    {
        $this->folder = config('const.FOLDER.ROOM');
        $this->room = $room;
        $this->imageServices = $imageServices;
    }

    /**
     * List rooms
     *
     * @param array $conditions
     * @param integer $paginate
     */
    public function index($conditions = [], $paginate = 10)
    {
        $rooms = DB::table('rooms')->selectRaw(
            "rooms.*,
            concat(houses.address,', ',districts.name, ', ', provinces.name)  as address"
        )->join("houses", "rooms.house_id", "=", "houses.id")
        ->join("provinces", "houses.province_id", "=", "provinces.id")
        ->join("districts", "houses.district_id", "=", "districts.id");
        $rooms = $this->filter($rooms, $conditions);
        $rooms = $rooms->whereNull('rooms.deleted_at');
        return $rooms->paginate($paginate);
    }

    /**
     * Filter for list rooms
     *
     * @param mixed $query
     * @param array $conditions []
     */
    private function filter($query, $conditions)
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
        $room = DB::transaction(function () use ($params) {
            $room = $this->room->create($params);
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
        $room = $this->room->findOrfail($id);
        if ($this->permission($room->house_id)) {
            $params['description'] = utf8_encode($params['description']);
            $room->update($params);
            return $room;
        } else {
            return abort(403, "You have no permission to update room");
        }
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
        $room = $this->room->findOrFail($id);
        if ($this->permission($room->house_id)) {
            $oldImages = json_decode($room->images);
            $oldImages = is_null($oldImages) ? [] : $oldImages;
            $newImages = $this->imageServices->store($images, $this->folder);
            $urls = array_merge($newImages, $oldImages);
            $room->images = json_encode($urls);
            $room->save();
            return $urls;
        } else {
            return abort(403, "You have no permission to add room's images");
        }
    }

     /**
     * Update room's images
     *
     * @param integer $id
     * @param array $images array of images url
     */
    public function updateImages($id, array $images)
    {
        $room = $this->room->findOrFail($id);
        if ($this->permission($room->house_id)) {
            $room->images = json_encode($images);
            $room->save();
        } else {
            return abort(403, "You have no permission to update room's images");
        }
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

    private function permission($houseId)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.ADMIN')) {
            return true;
        } elseif ($authUser->role==config('const.USER.ROLE.MANAGER')) {
            $selectRaw = DB::table('user_houses')->selectRaw("count(*) as sum")
                            ->where('house_id', $houseId)
                            ->where('user_id', $authUser->id)
                            ->first();
            return $selectRaw->sum > 0;
        }
        return false;
    }
}

