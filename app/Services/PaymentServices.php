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

    /**
     * List payment
     *
     * @param integer $paginate
     *
     * @return mixed
     */
    public function list($roomId = null, $paginate = 10)
    {
        $rent = $this->roomRepository->getRent($roomId);
        return $this->paymentRepository->list($rent->id, $paginate);
    }

    /**
     * Show payment
     *
     * @param integer $id
     *
     * @return \App\Models\Payment
     */
    public function show($id)
    {
        return $this->paymentRepository->findById($id);
    }

    /**
     * Create payment
     *
     * @param array $params
     *
     * @return \App\Models\Payment
     */
    public function store(array $params)
    {
        if (!$this->permission($params['room_id'])) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        $this->roomRepository->getRent($params['room_id']);
        $params['bill'] = json_encode($params['bill']);
        $params['creater_id'] = Auth::user()->id;
        $payment = $this->paymentRepository->store($params);
        return $payment;
    }

    /**
     * Update payment
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Payment
     */
    public function update($id, $params)
    {
        $payment = $this->paymentRepository->findById($id);
        $rent = $this->roomRepository->getRentById($payment->rent_room_id);
        if (!$this->permission($rent->room_id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        if (array_key_exists('bill', $params)) {
            $params['bill'] = json_encode($params['bill']);
        }
        return $this->paymentRepository->update($id, $params);
    }

    /**
     * Delete payment
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->paymentRepository->destroy($id);
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
