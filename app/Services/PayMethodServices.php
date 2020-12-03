<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\PayMethodRepository;

class PayMethodServices
{
    protected $payMethodRepository;

    public function __construct(PayMethodRepository $payMethodRepository)
    {
        $this->payMethodRepository = $payMethodRepository;
    }

    public function index($owner_id = null)
    {
        $owner_id = is_null($owner_id) ? Auth::user()->id : $owner_id;
        return $this->payMethodRepository->index($owner_id);
    }

    public function list($owner_id = null, $paginate = 10)
    {
        $owner_id = is_null($owner_id) ? Auth::user()->id : $owner_id;
        return $this->payMethodRepository->list($owner_id, $paginate);
    }

    /**
     * Create paymethod record
     *
     * @param array $params
     *
     * @return \App\Models\PayMethod
     */
    public function store(array $params)
    {
        $authUser = Auth::user();
        if (!array_key_exists('owner_id', $params)) {
            if ($authUser->role==config('const.USER.ROLE.OWNER')) {
                $params['owner_id'] = $authUser->id;
            } else {
                return abort(403, 'Owner required');
            }
        }
        $payMethod = $this->payMethodRepository->store($params);
        return $payMethod;
    }

    /**
     * Update
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Paymethod
     */
    public function update($id, array $params)
    {
        $this->payMethodRepository->update($id, $params);
    }

    /**
     * Delete PayMethod record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->payMethodRepository->destroy($id);
    }
}
