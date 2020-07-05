<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCriteria extends Model
{
	use SoftDeletes;

	protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
    	'room_id',
    	'criteria_id'
	];
	
	public function room()
	{
		return $this->belongsTo(Room::class);
	}

	public function criteria()
	{
		return $this->belongsTo(Criteria::class);
	}
}
