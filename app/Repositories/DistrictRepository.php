<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\District;

class DistrictRepository
{
    protected $district;
    protected $cacheTimeout;

    public function __construct(District $district)
    {
        $this->district = $district;
        $this->cacheTimeout = 604800;
    }

    /**
     * Get all district records
     *
     * @param integer||null $provinceId
     *
     * @return ([\App\Models\District])
     */
    public function all($provinceId = null)
    {
        $key = is_null($provinceId)?"districts":"districts#$provinceId";
        return Cache::remember($key, $this->cacheTimeout, function () use ($provinceId) {
            $district = $this->district;
            $district = !is_null($provinceId)?$district->where('province_id', $provinceId):$district;
            return $district->get();
        });
    }

    /**
     * List and paginate district records
     *
     * @param integer||null $provinceId
     * @param integer $paginate
     *
     * @return ([\App\Models\District])
     */
    public function list($provinceId = null, $paginate = 10)
    {
        $district = $this->district;
        if (is_null($provinceId)) {
            $district = $district->with('province');
        } else {
            $district = $district->where('province_id', $provinceId);
        }
        return $district->paginate($paginate);
    }

    /**
     * Create district record
     *
     * @param array $params
     *
     * @return \App\Models\District
     */
    public function store(array $params)
    {
        $district = $this->district->create($params);
        return $district;
    }

    /**
     * Find district record by id
     *
     * @param integer $id
     *
     * @return \App\Models\District
     */
    public function findById($id)
    {
        return $this->district->findOrFail($id);
    }

    /**
     * Find district record by slug
     *
     * @param string $slug
     *
     * @return \App\Models\District
     */
    public function findBySlug($slug)
    {
        return $this->district->where('slug', $slug)->first();
    }

    /**
     * Update district record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\District
     */
    public function update($id, array $params)
    {
        $district = $this->district->findOrFail($id);
        return DB::transaction(function () use ($district, $params) {
            $district->update($params);
            return $district;
        });
    }

    /**
     * Delete district record
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $district = $this->district->findOrfail($id);
        DB::transaction(function () use ($district) {
            $district->areas()->delete();
            $district->delete();
        });
    }
}
