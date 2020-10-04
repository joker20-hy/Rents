<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewRenter extends Model
{
    protected $primaryKey = ['review_id', 'renter_id'];
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'renter_id',
        'rate'
    ];
}
