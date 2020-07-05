<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\District;

class DistrictServices
{
    protected $district;

    public function __construct(District $district)
    {
        $this->district = $district;
    }

    public function index($provinceId = null)
    {
        return is_null($provinceId) ? $this->district->get()
                                    : $this->district->where('province_id', $provinceId)->get();
    }

    public function list($provinceId = null, $paginate = 10)
    {
        $query = is_null($provinceId) ? $this->district->with('province')
                                    : $this->district->where('province_id', $provinceId);
        return $query->paginate($paginate);
    }

    /**
     * Create a district
     * @param array $params ['name', 'province_id']
     */
    public function create($params)
    {
        $params['slug'] = Str::slug($params['name'], '-');
        $district = $this->district->create($params);
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
    public function update($id, $params)
    {
        $district = $this->district->findOrFail($id);
        $district->update($params);
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
        $district = $this->district->findOrFail($id);
        DB::transaction(function () use ($district) {
            $district->delete();
            if (count($district->areas)>0) {
                $district->areas->delete();
            }
        });
    }
}
