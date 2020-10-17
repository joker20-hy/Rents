<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'creator_id',
        'status',
        'time',
        'bill'
    ];

    public function room()
    {
        return $this->belongsTo(RentRoom::class, 'room_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
