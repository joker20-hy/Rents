<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repositories\AreaRepository;

class AreaServices
{
    protected $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * List all area by conditions
     *
     * @return mixed
     */
    public function index($paginate = 10)
    {
        return $this->areaRepository->list($paginate);
    }

    /**
     * Create area
     *
     * @param array $params [
     *  'name',
     *  'province_id',
     *  'district_id'
     * ]
     *
     * @return \App\Models\Area
     */
    public function create($params)
    {
        $params['slug'] = Str::slug($params['name'], '-');
        $params['slug'] = "khu-vuc-".$params['slug'];
        $area = $this->areaRepository->store($params);
        $area->province;
        $area->district;
        return $area;
    }

    /**
     * Update service
     *
     * @param integer $id
     * @param array $params [name, province_id, district_id]
     *
     * @return \App\Models\Area
     */
    public function update($id, $params)
    {
        $area = $this->areaRepository->update($id, $params);
        return $area;
    }

    /**
     * Delete an area
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->areaRepository->destroy($id);
        return true;
    }
}
