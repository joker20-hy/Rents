<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Province;

class ProvinceRepository
{
    protected $province;

    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    public function all()
    {
        return $this->province->select(['id', 'name', 'slug'])->get();
    }

    /**
     * List and paginate province records
     *
     * @param integer $paginate
     *
     * @return ([\App\Models\Province])
     */
    public function list($paginate)
    {
        return $this->province->paginate($paginate);
    }

    /**
     * Create province record
     *
     * @param array $params
     *
     * @return \App\Models\Province
     */
    public function store(array $params)
    {
        $province = DB::transaction(function () use ($params) {
            return $this->province->create($params);
        });
        return $province;
    }

    /**
     * Find province record by id
     *
     * @param integer $id
     *
     * @return \App\Models\Province
     */
    public function findById($id)
    {
        return $this->province->findOrFail($id);
    }

    /**
     * Find province record by slug
     *
     * @param string $slug
     *
     * @return \App\Models\Province
     */
    public function findBySlug($slug)
    {
        return $this->province->where('slug', $slug)->first();
    }

    /**
     * Update province record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Province
     */
    public function update($id, array $params)
    {
        $province = $this->province->findOrFail($id);
        $province = DB::transaction(function () use ($province, $params) {
            $province->update($params);
            return $province;
        });
        return $province;
    }

    /**
     * Delete province record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $province = $this->province->findOrfail($id);
            $province->delete();
            if (count($province->districts) > 0) {
                $province->districts->delete();
            }
        });
    }
}
