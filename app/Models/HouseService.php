<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'house_id',
        'service_id',
        'price'
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}