<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'province_id',
        'district_id',
        'area_id',
        'address',
        'address_detail',
        'latitude',
        'longitude',
        'avg_rate',
        'rate_count',
        'price',
        'rent',
        'acreage',
        'images',
        'direction',
        'description'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_houses');
    }

    public function userHouses()
    {
        return $this->hasMany(UserHouse::class);
    }

    public function houseServices()
    {
        return $this->hasMany(HouseService::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'house_services');
    }
}
