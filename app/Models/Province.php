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

    public function district()
    {
        return $this->hasMany(District::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
