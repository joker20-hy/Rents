<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
