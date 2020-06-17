<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
	use SoftDeletes;

    protected $fillable = [
    	'house_id',
    	'name',
    	'avg_rate',
    	'rate_count',
    	'acreage',
    	'images',
    	'description'
    ];

    public function house()
    {
    	return $this->belongTo(House::class);
    }
}
