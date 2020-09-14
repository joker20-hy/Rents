<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'from',
        'to'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}