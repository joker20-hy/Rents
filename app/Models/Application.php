<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'image',
        'status',
        'decline_reasons'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
