<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewOwner extends Model
{
    protected $primaryKey = ['review_id', 'owner_id'];
    public $incrementing = false;

    protected $fillable = [
        'review_id',
        'owner_id',
        'rate'
    ];
}
