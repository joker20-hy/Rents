<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPayMethod extends Model
{
    protected $fillable = [
        'room_id',
        'pay_method_id'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function paymethod()
    {
        return $this->belongsTo(PayMethod::class, 'pay_method_id');
    }
}
