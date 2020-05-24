<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Area;

define('PLACE_TYPE', [
    1 => [
        'KEY' => 1,
        'FOREIGN_KEY' => 'province_id'
    ],
    2 => [
        'KEY' => 2,
        'FOREIGN_KEY' => 'district_id'
    ],
    3 => [
        'KEY' => 3,
        'FOREIGN_KEY' => 'area_id'
    ]
]);

class AreaServices
{
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

    public function list($type = null, $id = null)
    {
        if (is_null($type) xor is_null($id)) {
            return response()->json(400, 'Missing foreign key or type');
        }
        $query = is_null($type) ? $this->area : $this->area->where(PLACE_TYPE[$type]['FOREIGN_KEY'], $id);
        return $query->get();
    }

    /**
     * @param array $params [
     *  'name',
     *  'province_id',
     *  'district_id'
     * ]
     * @return \App\Models\Area
     */
    public function store($params)
    {
        $params['slug'] = Str::slug($params['name'], '-');
        return $this->area->create($params);
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
        
    }
}
