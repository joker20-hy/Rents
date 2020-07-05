<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\District;
use App\Models\Province;

class AreaServices
{
    private $area;

    public function __construct(Area $area, Province $province, District $district)
    {
        $this->area = $area;
        $this->province = $province;
        $this->district = $district;
    }

    /**
     * @param integer $id
     * @return \App\Models\Area
     */
    public function find($id)
    {
        return $this->area->findOrFail($id);
    }

    /**
     * List all area by conditions
     *
     * @param integer $type
     * @param integer $id
     */
    public function index($type, $id, $paginate = 10)
    {
        $placeType = config('const.PLACE_TYPE');
        switch ($type) {
            case $placeType['PROVINCE']:
                return $this->area->where('province_id', $id)->paginate($paginate);
                break;
            case $placeType['DISTRICT']:
                return $this->area->where('district_id', $id)->paginate($paginate);
                break;
            default:
                return $this->area->with('province')->with('district')->paginate($paginate);
                break;
        }
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
        $area = $this->area->create($params);
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
        $area = $this->area->findOrFail($id);
        if ($params['name'] !== $area->name) {
            $params['slug'] = Str::slug($params['name'], '-');
            $params['slug'] = "khu-vuc-".$params['slug'];
        }
        $area->update($params);
        return $area;
    }

    /**
     * Delete an area
     * @param integer $id
     */
    public function destroy($id)
    {
        $area = $this->area->findOrFail($id);
        DB::transaction(function () use ($area) {
            $area->delete();
            if (count($area->houses)>0) {
                $area->houses->delete();
            }
        });
    }
}
