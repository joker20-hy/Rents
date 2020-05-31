<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Area;

class AreaServices
{
    private const PLACE_TYPE = [
        'PROVINCE' => 1,
        'DISTRICT' => 2,
    ];
    private $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
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
        switch ($type) {
            case self::PLACE_TYPE['PROVINCE']:
                return $this->area->where('province_id', $id)->paginate($paginate);
                break;
            case self::PLACE_TYPE['DISTRICT']:
                return $this->area->where('district_id', $id)->paginate($paginate);
                break;
            default:
                return $this->area->with('province')->with('district')->paginate($paginate);
                break;
        }
    }

    /**
     * @param array $params [
     *  'name',
     *  'province_id',
     *  'district_id'
     * ]
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
     * @param integer $id
     * @param array $params [name, province_id, district_id]
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
        $area->delete();
    }
}
