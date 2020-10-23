<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'anonymous',
        'like',
        'report'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renter()
    {
        return $this->belongsToMany(User::class, 'review_renters', 'renter_id');
    }

    public function owner()
    {
        return $this->belongsToMany(User::class, 'review_owners', 'owner_id');
    }

    public function house()
    {
        return $this->belongsToMany(House::class, 'review_houses', 'house_id');
    }

    public function room()
    {
        return $this->belongsToMany(Room::class, 'review_rooms', 'room_id');
    }

    public function reviewRooms()
    {
        return $this->hasOne(ReviewRoom::class);
    }
}
