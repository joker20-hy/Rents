<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Repositories\RoomRepository;
use App\Repositories\RenterRepository;
use App\Repositories\PaymentRepository;

class RenterServices
{
    private $roles;
    private $roomStatus;

    protected $userRepository;
    protected $roomRepository;
    protected $renterRepository;
    protected $paymentRepository;

    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository,
        RenterRepository $renterRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->roles = (object)config('const.USER.ROLE');
        $this->roomStatus = (object)config('const.ROOM_STATUS');
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->renterRepository = $renterRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Rent room
     *
     * @param integer $roomId
     * @param integer|null $userId
     *
     * @return \App\Models\User
     */
    public function rentRoom($roomId, $userId = null)
    {
        $userId = is_null($userId) ? Auth::user()->id : $userId;
        $user = $this->userRepository->findById($userId);
        if (!is_null($user->room_id)) {
            return abort(400, "Bạn đã đang là người thuê phòng");
        }
        return DB::transaction(function () use ($user, $roomId) {
            $renter = $this->userRepository->update(
                ['id' => $user->id],
                ['role' => $this->roles->RENTER, 'room_id' => $roomId]
            );
            $this->renterRepository->createRentRoomContract($renter->id, $roomId);
            $room = $this->roomRepository->findById($roomId);
            $roomUpdateParams = ['renter_count' => $room->renter_count + 1];
            if ($room->status!=$this->roomStatus->rented) {
                $roomUpdateParams['status'] = $this->roomStatus->rented;
            }
            $this->roomRepository->update($room->id, $roomUpdateParams);
            return $renter;
        });
    }

    /**
     * Leave room
     *
     * @param integer|null $userId
     *
     * @return \App\Models\User
     */
    public function leaveRoom($userId = null)
    {
        $authUser = Auth::user();
        if (!is_null($userId)) {
            $renter = $this->userRepository->findById($userId);
            if (is_null($renter->room_id)) {
                return abort(400, "Người này hiện không thuê trọ");
            }
            $room = $this->roomRepository->findById($renter->room_id);
            $this->ownerPermission($authUser, $room->house);
        } else {
            $renter = Auth::user();
            if ($renter->role!=$this->roles->RENTER) {
                return abort(403, "Bạn không thể thực hiện hành động này");
            }
            $room = $this->roomRepository->findById($renter->room_id);
        }
        $this->renterRepository->findRentRoomContract($room->id, $renter->id, true);
        return DB::transaction(function () use ($renter, $room) {
            $this->renterRepository->destroyRentRoomContract($room->id, $renter->id);
            $renter = $this->userRepository->update(
                ['id' => $renter->id],
                ['role' => $this->roles->NORMAL, 'room_id' => null]
            );
            $roomUpdateParams = ['renter_count' => $room->renter_count - 1];
            if ($room->renter_count == 1) {
                $this->paymentRepository->destroyByRoom($room->id);
                $roomUpdateParams['status'] = $this->roomStatus->waiting;
            }
            $this->roomRepository->update($room->id, $roomUpdateParams);
            return $renter;
        });
    }

    /**
     * Check owner permission
     *
     * @param \App\Models\User $user
     * @param \App\Models\House $house
     */
    private function ownerPermission($user, $house)
    {
        try {
            if ($user->role!=$this->roles->OWNER&&$user->role!=$this->roles->ADMIN) {
                return abort(403, 'Bạn không có quyền thực hiện hành động này');
            }
            if ($user->role==$this->roles->OWNER) {
                $ownerIds = $house->owners->pluck('id');
                if (!$ownerIds->contains($user->id)) {
                    return abort(403, 'Người thuê trọ này không thuê nhà trọ của bạn');
                }
            }
        } catch (Exception $e) {
            return abort(403, 'Bạn không có quyền thực hiện hành động này');
        }
    }
}
