<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\RoomPayment;

class PaymentRepository
{
    protected $roomPayment;

    public function __construct(RoomPayment $roomPayment)
    {
        $this->roomPayment = $roomPayment;
    }

    /**
     * Find first RoomPayment with conditions
     *
     * @param array $where
     */
    public function firstByRoom(array $where)
    {
        return $this->roomPayment->where($where)->firstOrFail();
    }

    /**
     * List payments by room
     *
     * @param integer $roomId
     * @param integer $paginate
     */
    public function listByRoom($roomId, $paginate)
    {
        return $this->roomPayment->where('room_id', $roomId)->paginate($paginate);
    }

    /**
     * Create payment for room
     *
     * @param array $params
     *
     * @return \App\Models\RoomPayment
     */
    public function storeForRoom(array $params)
    {
        return $this->roomPayment->create($params);
    }

    /**
     * Update payment
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Payment
     */
    public function updateByRoom($id, array $params)
    {
        $payment = $this->roomPayment->findOrFail($id);
        return DB::transaction(function () use ($payment, $params) {
            $payment->update($params);
            return $payment;
        });
        return $payment;
    }

    /**
     * Delete payment
     * 
     * @param integer $id
     */
    public function destroyByRoom($id)
    {
        return $this->roomPayment->where('room_id', $id)->delete();
    }
}
