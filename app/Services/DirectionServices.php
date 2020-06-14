<?php

namespace App\Services;

use App\Models\Direction;

class DirectionServices
{
    protected $direction;

    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
    }

    public function index()
    {
        $directions = $this->direction->get();
        return $directions;
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
        $direction = $this->direction->create($params);
        return $direction;
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
        $direction = $this->direction->findOrFail($id);
        $direction->update($params);
        return $direction;
    }

    public function destroy($id)
    {
        $direction = $this->direction->findOrFail($id);
        $direction->delete();
    }
}
