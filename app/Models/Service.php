<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'unit'];

    public function houses()
    {
        return $this->belongsToMany(House::class, 'house_services');
    }
}
