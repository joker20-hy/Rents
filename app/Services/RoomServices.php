<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\RoomRepository;

class RoomServices
{
    private $folder;
    protected $imageServices;
    protected $userServinces;
    protected $roomRepository;

    public function __construct(
        ImageServices $imageServices,
        UserServices $userServinces,
        RoomRepository $roomRepository
    ) {
        $this->folder = config('const.FOLDER.ROOM');
        $this->imageServices = $imageServices;
        $this->userServinces = $userServinces;
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
        return $this->roomRepository->get($conditions, $paginate);
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
        if ($authUser->role==config('USER.ROLE.OWNER')) {
            return $this->roomRepository->list($conditions, $authUser->id, $paginate);
        }
        return $this->roomRepository->list($conditions, null, $paginate);
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

    public function updateStatus($id, array $params)
    {
        if ($params['status']) {
            $this->roomRepository->update($id, ['status' => $params['status']]);
        } else {
            $this->roomRepository->emptyRoom($id);
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
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to delete room");
        }
        $this->roomRepository->delete($id);
    }

    /**
     * Get room with services
     *
     * @param integer $id
     *
     * @return array
     */
    public function getServices($id)
    {
        if (!$this->permission($id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        $room = $this->roomRepository->findById($id);
        $room->renters_count = $room->renters->count();
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
     * Add PayMethod to Room
     *
     * @param integer $id
     * @param array $payMethodIds
     *
     * @return \App\Models\Room
     */
    public function addPayMethods($id, array $payMethodIds)
    {
        $this->roomRepository->updatePayMethods($id, $payMethodIds);
    }

    /**
     * @param integer $id
     * @param boolean $all
     * @param array $userIds
     */
    public function leaveRoom($id, $all = false, $userIds = [])
    {
        if ($all) {
            $this->roomRepository->emptyRoom($id);
        } else {
            foreach($userIds as $userId) {
                $this->userServinces->leaveRoom($userId);
            }
        }
    }

    /**
     * Find all renters of a room by id
     *
     * @param integer $id
     *
     * @return array
     */
    public function renters($id)
    {
        if (!$this->permission($id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        $room = $this->roomRepository->findById($id);
        $renters = $room->renters;
        foreach ($renters as $renter) {
            $renter->profile;
        }
        return $renters;
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

