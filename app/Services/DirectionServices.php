<?php

namespace App\Services;

use App\Repositories\DirectionRepository;

class DirectionServices
{
    protected $directionRepository;

    public function __construct(DirectionRepository $directionRepository)
    {
        $this->directionRepository = $directionRepository;
    }

    public function index()
    {
        return $this->directionRepository->all();
    }

    /**
     * Create direction record
     *
     * @param array $params
     *
     * @return \App\Models\Direction
     */
    public function store(array $params)
    {
        return $this->directionRepository->store($params);
    }

    /**
     * Update direction record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Direction
     */
    public function update($id, array $params)
    {
        return $this->directionRepository->update($id, $params);
    }

    /**
     * Delete direction
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->directionRepository->destroy($id);
    }
}
