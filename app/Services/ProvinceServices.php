<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Province;

class ProvinceServices
{
    protected $province;

    /**
     * @param \App\Models\Province $province
     */
    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    /**
     * Get all province
     */
    public function index($paginate = 10)
    {
        return $this->province->paginate($paginate);
    }

    /**
     * Create a province
     * @param Array $params []
     * @return \App\Models\Province
     */
    public function create($params)
    {
        $params['slug'] = Str::slug($params['name'], '-');
        return $this->province->create($params);
    }

    public function destroy($id)
    {
        $province = $this->province->findOrfail($id);
        $province->delete();
    }
}
