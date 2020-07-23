<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avg_rate',
        'rate_count',
        'verify'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \App\Models\Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return \App\Models\Setting
     */
    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function houses()
    {
        return $this->belongsToMany(House::class, 'user_houses');
    }

    public function userHouses()
    {
        return $this->hasMany(UserHouse::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
