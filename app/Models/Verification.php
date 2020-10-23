<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Verification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'expire_at',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
