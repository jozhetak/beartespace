<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	protected $fillable = [ 'address', 'cart', 'status' ];

	public function user() {
		return $this->belongsTo( User::class );
	}

	public function address() {
		return $this->hasOne( Address::class );
	}

	public function payment() {
		return $this->hasOne(Payment::class);
	}

	public function scopeConfirmed( $query ) {
		return $query->where( 'confirmed_at', '<', now() );
	}
}
