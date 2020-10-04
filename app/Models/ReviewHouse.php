<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewHouse extends Model
{
    protected $primaryKey = ['review_id', 'house_id'];
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'house_id',
        'secure_rate'
    ];
}
