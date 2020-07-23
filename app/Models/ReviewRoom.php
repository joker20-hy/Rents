<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewRoom extends Model
{
    use SoftDeletes;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'room_id',
        'secure_rate',
        'infra_rate'
    ];
}
