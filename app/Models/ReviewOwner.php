<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewOwner extends Model
{
    use SoftDeletes;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'owner_id',
        'rate'
    ];
}
