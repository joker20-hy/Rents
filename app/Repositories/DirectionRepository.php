<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Direction;

class DirectionRepository
{
    protected $direction;

    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
    }

    public function all()
    {
        return $this->direction->get();
    }

    /**
     * Get and paginate directions
     *
     * @param integer $paginate
     *
     * @return ([\App\Models\Direction])
     */
    public function list($paginate)
    {
        return $this->direction->paginate($paginate);
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
        return $this->direction->create($params);
    }

    /**
     * Update direction record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Direction
     */
    public function update($id, $params)
    {
        $direction = DB::transaction(function () use ($id, $params) {
            $direction = $this->direction->findOrFail($id);
            $direction->update($params);
            return $direction;
        });
        return $direction;
    }

    /**
     * Delete direction
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $direction = $this->direction->findOrFail($id);
            $direction->delete();
        });
    }
}