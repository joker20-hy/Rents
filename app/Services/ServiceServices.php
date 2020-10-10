<?php

namespace App\Services;

use App\Repositories\ServiceRepository;

class ServiceServices
{
    protected $service;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index()
    {
        return $this->serviceRepository->all();
    }

    /**
     * @param integer $paginate
     */
    public function list($paginate = 10)
    {
        return $this->serviceRepository->list($paginate);
    }

    /**
     * Create service
     *
     * @param array $params
     *
     * @return \App\Models\Service
     */
    public function store(array $params)
    {
        $params['unit'] = array_key_exists('unit', $params)?$params['unit']:'người';
        $params['unit'] = $params['type']==config('const.SERVICE_TYPE.BY_RENTERS')?'người':$params['unit'];
        return $this->serviceRepository->store($params);
    }

    /**
     * Update service
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Service
     */
    public function update($id, array $params)
    {
        return $this->serviceRepository->update($id, $params);
    }

    /**
     * Delete service
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->serviceRepository->destroy($id);
    }
}
