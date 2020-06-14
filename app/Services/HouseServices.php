<?php

namespace App\Services;

use App\Models\House;

class HouseServices
{
    private const PAGINATE = 10;
    private $folder;
    protected $house;
    protected $imageServices;

    public function __construct(House $house, ImageServices $imageServices)
    {
        $this->house = $house;
        $this->imageServices = $imageServices;
        $this->folder = config('const.FOLDER.HOUSE');
    }

    public function index($province = null, $district = null, $area = null)
    {
        $houses = $this->house;
        if (!is_null($province)) {
            $houses = $houses->where('province_id', $province);
        }
        if (!is_null($district)) {
            $houses = $houses->where('district_id', $district);
        }
        if (!is_null($area)) {
            $houses = $houses->where('area_id', $area);
        }
        $houses = $houses->with('province')->with('district')->with('area')->paginate(self::PAGINATE);
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
            $images = $this->imageServices->store($params['images']);
            $params['images'] = json_encode($images);
        }
        if (isset($params['description'])) {
            $params['description'] = utf8_encode($params['description']);
        }
        $house = $this->house->create($params);
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
        $house = $this->house->findOrfail($id);
        if (is_null($house)) {
            return abort(404, 'Could not find this house');
        }
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
        $house = $this->house->findOrfail($id);
        if (is_null($house)) {
            return abort(404, 'Could not find this house');
        }
        $house->update($params);
        return $house;
    }

    public function uploadImages($id, array $images)
    {
        $house = $this->house->findOrFail($id);
        $oldImages = json_decode($house->images);
        $oldImages = is_null($oldImages) ? [] : $oldImages;
        $newImages = $this->imageServices->store($images, $this->folder);
        $urls = array_merge($newImages, $oldImages);
        $house->images = json_encode($urls);
        $house->save();
        return $urls;
    }

    public function updateImages($id, array $images)
    {
        $house = $this->house->findOrFail($id);
        $house->images = json_encode($images);
        $house->save();
    }
}
