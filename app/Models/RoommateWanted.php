<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoommateWanted extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'creator_id',
        'number',
        'contact',
        'content'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
