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
		'description',
		'price',
		'cycle',
		'status',
		'renter_count'
    ];

    public function house()
    {
    	return $this->belongsTo(House::class);
	}

	public function criterias()
	{
		return $this->belongsToMany(Criteria::class, 'room_criterias');
	}

	public function renters()
	{
		return $this->hasMany(User::class);
	}

	public function payments()
	{
		return $this->hasMany(RoomPayment::class);
	}

	public function paymethods()
    {
        return $this->belongsToMany(PayMethod::class, 'room_pay_methods');
    }
}
