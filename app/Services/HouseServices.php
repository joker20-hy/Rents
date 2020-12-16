<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\ProvinceRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\AreaRepository;
use App\Repositories\HouseRepository;

class HouseServices
{
    protected $folder;
    protected $imageServices;
    protected $provinceRepository;
    protected $districtRepository;
    protected $areaRepository;
    protected $houseRepository;

    public function __construct(
        ImageServices $imageServices,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        AreaRepository $areaRepository,
        HouseRepository $houseRepository
    ) {
        $this->imageServices = $imageServices;
        $this->folder = config('const.FOLDER.HOUSE');
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->areaRepository = $areaRepository;
        $this->houseRepository = $houseRepository;
    }

    public function list($conditions, $paginate = 10)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.OWNER')) {
            $ids = $authUser->houses->pluck('id');
            $houses = $this->houseRepository->ownerList($ids, $conditions, $paginate);
        } else {
            $houses = $this->houseRepository->adminList($conditions, $paginate);
        }
        return $houses;
    }

    /**
     * Create a house
     *
     * @param array $params
     *
     * @return \App\Models\House
     */
    public function store(array $params)
    {
        if (isset($params['images'])) {
            $images = $this->imageServices->store($params['images'], $this->folder);
            $params['images'] = json_encode($images);
        }
        if (isset($params['description'])) {
            $params['description'] = utf8_encode($params['description']);
        }
        $province = $this->provinceRepository->findById($params['province_id']);
        $district = $this->districtRepository->findById($params['district_id']);
        $area = $this->areaRepository->findById($params['area_id']);
        $params['address_detail'] = implode(", ", [$params['address'], $area->name, $district->name, $province->name]);
        $house = $this->houseRepository->store($params, Auth::user()->id);
        return $house;
    }

    /**
     * Show info of house
     *
     * @param integer $id
     *
     * @return \App\Models\House
     */
    public function show($id)
    {
        $house = $this->houseRepository->findById($id);
        $house->province;
        $house->district;
        $house->area;
        $house->houseServices;
        return $house;
    }

    /**
     * Update house info
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\House
     */
    public function update($id, array $params)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to update house");
        }
        if (array_key_exists('description', $params)) {
            $params['description'] = utf8_encode($params['description']);
        }
        $house = $this->houseRepository->update($id, $params);
        return $house;
    }

    /**
     * Upload new house's images
     *
     * @param integer $id
     * @param array $images array of image files
     *
     * @return array
     */
    public function uploadImages($id, array $images)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to add house's images");
        }
        $images = $this->imageServices->store($images, $this->folder, 'h');
        $images = $this->houseRepository->addImages($id, $images);
        return $images;
    }

    /**
     * Update house's images
     *
     * @param integer $id
     * @param array $images array of images url
     */
    public function updateImages($id, array $images)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to update house's images");
        }
        $house = $this->houseRepository->update($id, [
            'images' => json_encode($images)
        ]);
        return $house;
    }

    public function deleteImages($id, array $images)
    {

    }

    /**
     * Get House services
     * @param integer $id
     *
     * @return Collection
     */
    public function services($id)
    {
        return $this->houseRepository->services($id);
    }

    /**
     * Delete house
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        if (!$this->permission($id)) {
            return abort(403, "You have no permission to delete this house");
        }
        $this->houseRepository->destroy($id);
    }

    /**
     * Check if user has permission
     *
     * @param integer $id
     *
     * @return boolean
     */
    private function permission($id)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.ADMIN')) {
            return true;
        } elseif ($authUser->role==config('const.USER.ROLE.OWNER')) {
            $houseIds = $authUser->houses->pluck('id');
            return $houseIds->contains($id);
        }
        return false;
    }
}
