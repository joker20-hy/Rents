<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\PayMethod;

class PayMethodRepository
{
    protected $paymethod;

    public function __construct(Paymethod $paymethod)
    {
        $this->paymethod = $paymethod;
    }

    public function index($owner_id = null)
    {
        if (is_null($owner_id)) {
            return $this->paymethod->get();
        }
        return $this->paymethod->where('owner_id', $owner_id)->get();
    }

    public function list($owner_id = null, $paginate = 10)
    {
        if (is_null($owner_id)) {
            return $this->paymethod->paginate($paginate);
        }
        return $this->paymethod->where('owner_id', $owner_id)->paginate($paginate);
    }

    /**
     * Create paymethod record
     *
     * @param array $params
     *
     * @return \App\Models\Paymethod
     */
    public function store(array $params)
    {
        $paymethod = DB::transaction(function () use ($params) {
            return $this->paymethod->create($params);
        });
        return $paymethod;
    }

    /**
     * Update paymethod record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Paymethod
     */
    public function update($id, array $params)
    {
        $paymethod = $this->paymethod->findOrFail($id);
        DB::transaction(function () use ($paymethod, $params) {
            $paymethod->update($params);
        });
        return $paymethod;
    }

    /**
     * Delete paymethod record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $paymethod = $this->paymethod->findOrFail($id);
        DB::transaction(function () use ($paymethod) {
            $paymethod->delete();
        });
    }
}
