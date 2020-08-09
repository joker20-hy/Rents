<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\House;
use App\Models\UserHouse;
use App\Models\HouseService;

class HouseRepository
{
    protected $house;

    public function __construct(House $house, UserHouse $userHouse, HouseService $houseService)
    {
        $this->house = $house;
        $this->userHouse = $userHouse;
        $this->houseService = $houseService;
    }

    /**
     * List houses if user is admin
     *
     * @param array $conditions
     * @param integer $paginate
     *
     * @return mixed
     */
    public function adminList(array $conditions, $paginate)
    {
    	return $this->listThroughFilter($this->house, $conditions, $paginate);
    }

    /**
     * List houses if user is house owner
     *
     * @param array $houseIds
     * @param array $conditions
     * @param integer $paginate
     *
     * @return mixed
     */
    public function ownerList($houseIds, array $conditions, $paginate)
    {
    	$houses = $this->house->whereIn('id', $houseIds);
    	return $this->listThroughFilter($houses, $conditions, $paginate);
    }

    /**
     * List houses with conditions
     *
     * @param mixed $query
     * @param array $conditions
     * @param integer $paginate
     *
     * @return mixed
     */
    private function listThroughFilter($query, array $conditions, $paginate)
    {
        if (isset($conditions['area'])) {
            $query = $query->where('area_id', $conditions['area']);
        } elseif (isset($conditions['district'])) {
            $query = $query->where('district_id', $conditions['district']);
        } elseif (isset($conditions['province'])) {
            $query = $query->where('province_id', $conditions['province']);
        }
        return $query->with('houseServices')->with('province')
                    ->with('district')->with('area')
                    ->paginate($paginate);
    }

    /**
     * Find house id
     *
     * @param integer $id
     *
     * @return \App\Models\House
     */
    public function findById($id)
    {
    	return $this->house->findOrFail($id);
    }

    /**
     * Create new house record
     *
     * @param array $params
     * @param integer $ownerId
     *
     * @return \App\Models\House
     */
    public function store(array $params, $ownerId)
    {
        $house = DB::transaction(function () use ($params, $ownerId) {
        	$house = $this->house->create($params);
	        $this->userHouse->create([
                'user_id' => $ownerId,
                'house_id' => $house->id,
                'role' => config('const.OWNER_ROLE.OWNER')
            ]);
            $houseServices = [];
            foreach($params['services'] as $serv) {
                $serv = json_decode($serv);
                array_push($houseServices, [
                    'house_id' => $house->id,
                    'service_id' => $serv->id,
                    'price' => $serv->price
                ]);
            }
            $this->houseService->insert($houseServices);
	        return $house;
        });
        return $house;
    }

    /**
     * Create new house record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\House
     */
    public function update($id, array $params)
    {
    	$house = DB::transaction(function () use ($id, $params) {
    		$house = $this->house->findOrFail($id);
            $house->update($params);
            $houseServices = [];
            foreach($params['add_services'] as $serv) {
                array_push($houseServices, [
                    'house_id' => $house->id,
                    'service_id' => $serv['id'],
                    'price' => $serv['price']
                ]);
            }
            $this->houseService->insert($houseServices);
            $this->houseService->where('house_id', $house->id)
                ->whereIn('service_id', $params['remove_services'])
                ->delete();
        	return $house;
        });
        return $house;
    }

    /**
     * Add house images
     *
     * @param integer $id
     * @param array $newImages
     *
     * @return array
     */
    public function addImages($id, array $newImages)
    {
    	$images = DB::transaction(function () use ($id, $newImages) {
    		$house = $this->house->findOrFail($id);
            $oldImages = is_null($house->images) ? [] : json_decode($house->images);
    		$images = array_merge($newImages, $oldImages);
    		$house->update(['images' => json_encode($images)]);
        	return $images;
        });
        return $images;
    }

    /**
     * Delete house by id
     *
     * @param integer $id
     *
     * @return \App\Models\House
     */
    public function destroy($id)
    {
    	DB::transaction(function () use ($id) {
    		$house = $this->house->findOrFail($id);
    		$house->delete();
            $house->rooms()->delete();
            $house->houseServices()->delete();
        });
        return true;
    }
}
