<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'time',
        'creater_id',
        'bill',
        'status'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function creater()
    {
        return $this->belongsTo(User::class, 'creater_id');
    }
}
