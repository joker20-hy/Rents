<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\House;

class HouseServices
{
    private const PAGINATE = 10;
    private $folder;
    protected $house;
    protected $imageServices;
    protected $userHouseServices;

    public function __construct(
        House $house,
        ImageServices $imageServices,
        UserHouseServices $userHouseServices
    ) {
        $this->house = $house;
        $this->imageServices = $imageServices;
        $this->userHouseServices = $userHouseServices;
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
        $houses = $houses->with('province')->with('district')->with('area')
                        ->paginate(self::PAGINATE);
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
        $house = DB::transaction(function () use ($params) {
            $house = $this->house->create($params);
            $this->userHouseServices->store([
                'user_id' => Auth::user()->id,
                'house_id' => $house->id,
                'role' => config('const.OWNER_ROLE.OWNER')
            ]);
            return $house;
        });
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
        $this->permission($house);
        if (is_null($house)) {
            return abort(404, 'Could not find this house');
        } elseif (!$this->permission($house)) {
            return abort(403, "You have no permission to update house");
        }
        $house->update($params);
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
        $house = $this->house->findOrFail($id);
        if ($this->permission($house)) {
            $oldImages = json_decode($house->images);
            $oldImages = is_null($oldImages) ? [] : $oldImages;
            $newImages = $this->imageServices->store($images, $this->folder);
            $urls = array_merge($newImages, $oldImages);
            $house->images = json_encode($urls);
            $house->save();
            return $urls;
        } else {
            return abort(403, "You have no permission to add house's images");
        }
    }

    /**
     * Update house's images
     *
     * @param integer $id
     * @param array $images array of images url
     */
    public function updateImages($id, array $images)
    {
        $house = $this->house->findOrFail($id);
        if ($this->permission($house)) {
            $house->images = json_encode($images);
            $house->save();
        } else {
            return abort(403, "You have no permission to update house's images");
        }
    }

    /**
     * Delete house
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $house = $this->house->findOrFail($id);
        if ($this->permission($house->id)) {
            $house->delete();
        } else {
            return abort(403, "You have no permission to delete this house");
        }
    }

    private function permission($house)
    {
        $authUser = Auth::user();
        if ($authUser->role==config('const.USER.ROLE.ADMIN')) {
            return true;
        } elseif ($authUser->role==config('const.USER.ROLE.MANAGER')) {
            $selectRaw = DB::table('user_houses')->selectRaw("count(*) as sum")
                            ->where('house_id', $house->id)
                            ->where('user_id', $authUser->id)
                            ->first();
            return $selectRaw->sum > 0;
        }
        return false;
    }
}
