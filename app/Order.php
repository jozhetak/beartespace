<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function address() {
    	return $this->hasOne(Address::class);
    }

	public function scopeConfirmed($query)
	{
		return $query->where('confirmed_at', '<', now());
	}}
