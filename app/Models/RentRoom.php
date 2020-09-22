<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'renter_id',
        'room_id',
        'from',
        'to'
    ];

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}