<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewRenter extends Model
{
    use SoftDeletes;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'renter_id',
        'rate'
    ];
}
