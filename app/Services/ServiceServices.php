<?php

namespace App\Services;

use App\Models\Service;

class ServiceServices
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index($paginate = 10)
    {
        $services = $this->service->paginate($paginate);
        return $services;
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
        $service = $this->service->create($params);
        return $service;
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
        $service = $this->service->findOrFail($id);
        $service->update($params);
        return $service;
    }

    /**
     * Delete service
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $service = $this->service->findOrFail($id);
        $service->delete($id);
    }
}
