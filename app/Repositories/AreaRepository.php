<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Area;

class AreaRepository
{
    protected $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * List areas
     *
     * @param integer $paginate
     *
     * @return mixed
     */
    public function list($paginate = 10)
    {
        return $this->area->with('province')->with('district')->paginate($paginate);
    }

    /**
     * Create area record
     *
     * @param array $params
     *
     * @return \App\Models\Area
     */
    public function store(array $params)
    {
        $area = $this->area->create($params);
        return $area;
    }

    /**
     * Find area record by id
     *
     * @param integer $id
     *
     * @return \App\Models\Area
     */
    public function findById($id)
    {
        return $this->area->findOrFail($id);
    }

    /**
     * Find area record by slug
     *
     * @param string $slug
     *
     * @return \App\Models\Area
     */
    public function findBySlug($slug)
    {
        return $this->area->where('slug', $slug)->first();
    }

    /**
     * Update area record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Area
     */
    public function update($id, array $params)
    {
        $area = DB::transaction(function () use ($id, $params) {
            $area = $this->area->findOrFail($id);
            $area->update($params);
            return $area;
        });
        return $area;
    }

    /**
     * Delete area record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $area = $this->area->findOrFail($id);
            if (count($area->houses) > 0) {
                $area->houses->delete();
            }
            $area->delete();
        });
    }
}
