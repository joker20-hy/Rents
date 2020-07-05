<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criteria extends Model
{
	use SoftDeletes;

    protected $fillable = [
		'name',
		'icon'
    ];

    public function rooms()
	{
		return $this->belongsToMany(Room::class, 'room_criterias');
	}
}
