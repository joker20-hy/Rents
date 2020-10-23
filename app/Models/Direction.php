<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'name'
    ];

    public function house()
    {
    	return $this->hasMany(House::class);
    }
}
