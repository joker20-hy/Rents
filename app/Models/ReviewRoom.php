<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewRoom extends Model
{
    protected $primaryKey = ['review_id', 'room_id'];
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'room_id',
        'secure_rate',
        'infra_rate'
    ];
}
