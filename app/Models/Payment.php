<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rent_room_id',
        'creater_id',
        'time',
        'bill',
        'status'
    ];

    public function creater()
    {
        return $this->belongsTo(User::class, 'creater_id');
    }
}
