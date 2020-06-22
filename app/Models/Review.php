<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receiver_type',
        'receiver_id',
        'title',
        'rate',
        'description',
        'anonymous',
        'like',
        'report'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
