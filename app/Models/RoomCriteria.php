<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCriteria extends Model
{
	protected $primaryKey = ['room_id', 'criteria_id'];
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
