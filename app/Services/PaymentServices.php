<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\PaymentRepository;
use App\Repositories\RoomRepository;

class PaymentServices
{
    protected $paymentRepository;
    protected $roomRepository;

    public function __construct(
        PaymentRepository $paymentRepository,
        RoomRepository $roomRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->roomRepository = $roomRepository;
    }

    public function listByRoom($roomId, $paginate = 10)
    {
        return $this->paymentRepository->listByRoom($roomId, $paginate);
    }

    /**
     * Show payment
     *
     * @param integer $id
     *
     * @return \App\Models\Payment
     */
    public function showByRoom($roomId)
    {
        return $this->paymentRepository->firstByRoom(['room_id' => $roomId]);
    }

    /**
     * Create RoomPayment record
     *
     * @param array $params
     *
     * @return \App\Models\RoomPayment
     */
    public function storeForRoom(array $params)
    {
        if (!$this->permission($params['room_id'])) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        $params['creator_id'] = Auth::user()->id;
        $params['bill'] = json_encode($params['bill']);
        return $this->paymentRepository->storeForRoom($params);
    }

    public function updateByRoom($id, $params)
    {
        $payment = $this->paymentRepository->firstByRoom(['id' => $id]);
        if (!$this->permission($payment->room_id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        if (array_key_exists('bill', $params)) {
            $params['bill'] = json_encode($params['bill']);
        }
        return $this->paymentRepository->updateByRoom(['id' => $payment->id], $params);
    }

    public function destroyByRoom($roomId)
    {
        $this->paymentRepository->destroyByRoom($roomId);
    }

    /**
     * Check if current user has permission
     *
     * @param integer $id
     *
     * @return boolean
     */
    private function permission($roomId)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.ADMIN')) {
            return true;
        } elseif ($authUser->role==config('const.USER.ROLE.OWNER')) {
            $room = $this->roomRepository->findById($roomId);
            $houseIds = $authUser->houses->pluck('id');
            return $houseIds->contains($room->house_id);
        }
        return false;
    }
}
