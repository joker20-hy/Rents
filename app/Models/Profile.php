<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'image',
        'phone',
        'address',
        'date_of_birth'
    ];

    /**
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function avatar()
    {
        $this->image = is_null($this->image) ? asset('images/default.svg') : $this->image;
    }
}
