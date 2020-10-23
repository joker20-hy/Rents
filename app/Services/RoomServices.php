<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;
use App\Repositories\RenterRepository;

class RoomServices
{
    private $folder;
    protected $imageServices;
    protected $roomRepository;
    protected $userRepository;
    protected $renterRepository;
    protected $paymentRepository;

    public function __construct(
        ImageServices $imageServices,
        RoomRepository $roomRepository,
        UserRepository $userRepository,
        RenterRepository $renterRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->folder = config('const.FOLDER.ROOM');
        $this->imageServices = $imageServices;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->renterRepository = $renterRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Get an paginate rooms
     *
     * @param array $conditions
     * @param integer $paginate
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(array $conditions = [], $paginate = 10)
    {
        if (array_key_exists('sort', $conditions)) {
            $conditions['sort'] = config('const.ROOM_SORT')[$conditions['sort']];
        }
        if (array_key_exists('price', $conditions)) {
            $conditions['price'] = config('const.ROOM_FILTER.PRICE')[$conditions['price']];
        }
        if (array_key_exists('acreage', $conditions)) {
            $conditions['acreage'] = config('const.ROOM_FILTER.ACREAGE')[$conditions['acreage']];
        }
        return $this->roomRepository->index($conditions, $paginate);
    }

    /**
     * List rooms
     *
     * @param array $conditions
     * @param integer $paginate
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
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
        return $this->roomRepository->first(['id' => $id], ['criterias', 'house']);
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
     * Update status of room record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Room
     */
    public function updateStatus($id, array $params)
    {
        switch ($params['status']) {
            case config('const.ROOM_STATUS.waiting'):
                return $this->empty($id);
                break;
            default:
                return $this->roomRepository->update($id, ['status' => $params['status']]);
                break;
        }
    }

    /**
     * Remove all renter of a room
     *
     * @param integer $id
     *
     * @return \App\Models\Room
     */
    public function empty($id)
    {
        $room = $this->roomRepository->findById($id);
        return DB::transaction(function () use ($room) {
            $this->roomRepository->update($room->id, [
                'status' => config('const.USER.ROLE.NORMAL'),
                'renter_count' => 0
            ]);
            $this->userRepository->update(
                ['room_id' => $room->id],
                ['room_id' => null, 'role' => config('const.ROOM_STATUS.waiting')]
            );
            $this->renterRepository->destroyRentRoomContract($room->id);
            $this->paymentRepository->destroyByRoom($room->id);
            return $room;
        });
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
        $roomServices = $room->house->houseServices;
        foreach($roomServices as $roomService) {
            $roomService->service;
        }
        return ['room' => $room, 'room_services' => $roomServices];
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
        return $this->userRepository->find(['room_id' => $room->id], ['profile']);
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

