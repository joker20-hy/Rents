<?php

namespace App\Services;

use App\Repositories\DistrictRepository;

class DistrictServices
{
    protected $districtRepository;

    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    /**
     * List all district records
     */
    public function index($provinceId = null)
    {
        return $this->districtRepository->all($provinceId);
    }

    /**
     * List and paginate district records
     */
    public function list($provinceId = null, $paginate = 10)
    {
        return $this->districtRepository->list($provinceId, $paginate);
    }

    /**
     * Create a district
     *
     * @param array $params [
     *  name: {String},
     *  province_id: {Integer}
     * ]
     *
     * @return \App\Models\District
     */
    public function store(array $params)
    {
        $district = $this->districtRepository->store($params);
        $district->province;
        return $district;
    }

    /**
     * Update a district
     *
     * @param integer $id
     * @param array $params ['name' , 'slug', 'province_id']
     *
     * @return \App\Models\District
     */
    public function update($id, array $params)
    {
        $district = $this->districtRepository->update($id, $params);
        $district->province;
        return $district;
    }

    /**
     * Delete a district
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->districtRepository->destroy($id);
    }
}
