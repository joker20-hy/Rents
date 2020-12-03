<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Repositories\ProvinceRepository;

class ProvinceServices
{
    private const LIST_ALL = 'all';
    protected $province;
    protected $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepository, Province $province)
    {
        $this->provinceRepository = $provinceRepository;
        $this->province = $province;
    }

    public function all()
    {
        return $this->provinceRepository->all();
    }

    /**
     * Get all province
     */
    public function index($paginate = 10)
    {
        if ($paginate == self::LIST_ALL) {
            return $this->provinceRepository->all();
        }
        $paginate = is_null($paginate) ? 10 : $paginate;
        return $this->provinceRepository->list($paginate);
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
        return $this->provinceRepository->store($params);
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
        return $this->provinceRepository->update($id, $params);
    }

    /**
     * Delete a province
     *
     * @param UnsignedBigInteger $id
     */
    public function destroy($id)
    {
        $this->provinceRepository->destroy($id);
    }
}
