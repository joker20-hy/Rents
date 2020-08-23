<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class PaymentRepository
{
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Show payment
     *
     * @param integer $id
     *
     * @return \App\Models\Payment
     */
    public function findById($id)
    {
        return $this->payment->findOrFail($id);
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
        $payments = $this->payment;
        if (!is_null($roomId)) {
            $payments = $payments->where('room_id', $roomId);
        }
        return $payments->orderBy('created_at', 'desc')->paginate($paginate);
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
        $payment = DB::transaction(function () use ($params) {
            return $this->payment->create($params);
        });
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
    public function update($id, array $params)
    {
        $payment = $this->payment->findOrFail($id);
        $payment = DB::transaction(function () use ($payment, $params) {
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
    public function destroy($id)
    {
        $payment = $this->payment->findOrFail($id);
        DB::transaction(function () use ($payment) {
            $payment->delete();
        });
    }
}
