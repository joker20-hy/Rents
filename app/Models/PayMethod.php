<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'account',
        'note'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
