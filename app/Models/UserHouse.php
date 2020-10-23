<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHouse extends Model
{
    protected $fillable = [
        'user_id',
        'house_id',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
