<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'creater_id',
        'time',
        'bill',
        'status'
    ];

    public function creater()
    {
        return $this->belongsTo(User::class, 'creater_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
