<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServiceRepository
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;        
    }

    /**
     * List and paginate service records
     *
     * @param integer $paginate
     *
     * @return ([App\Models\Service])
     */
    public function list($paginate)
    {
        return $this->service->paginate($paginate);
    }

    /**
     * Create service record
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
     * Update service record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Service
     */
    public function update($id, $params)
    {
        $service = DB::transaction(function () use ($id, $params) {
            $service = $this->service->findOrFail($id);
            $service->update($params);
            return $service;
        });
        return $service;
    }

    /**
     * Delete service record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $service = $this->service->findOrFail($id);
            $service->delete($id);
        });
    }
}
