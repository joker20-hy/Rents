<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Province;

class ProvinceServices
{
    private const LIST_ALL = 'all';
    protected $province;

    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    /**
     * Get all province
     */
    public function index($perPage = 10)
    {
        if ($perPage == self::LIST_ALL) {
            return $this->province->select(['id', 'name'])->get();
        }
        $perPage = is_null($perPage) ? 10 : $perPage;
        return $this->province->paginate($perPage);
    }

    /**
     * Create a province
     *
     * @param Array $params []
     *
     * @return \App\Models\Province
     */
    public function create($params)
    {
        $params['slug'] = Str::slug($params['name'], '-');
        return $this->province->create($params);
    }

    /**
     * Update a province
     *
     * @param UnsignedBigInteger $id
     * @param Array $params
     *
     * @return \App\Models\Province
     */
    public function update($id, $params)
    {
        $province = $this->province->findOrFail($id);
        $province->update($params);
        return $province;
    }

    /**
     * Delete a province
     *
     * @param UnsignedBigInteger $id
     */
    public function destroy($id)
    {
        $province = $this->province->findOrfail($id);
        $province->delete();
    }
}
