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
}
