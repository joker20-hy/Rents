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

    public function index($paginate = 10)
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
