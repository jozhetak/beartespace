<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Add;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Artwork extends Model implements Buyable {

	protected $guarded = [];

	protected $appends = [
		'formatted_price',
		'image_url'
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
		'date_of_completion'
	];

	protected $casts = [
		'tags'      => 'array',
		'medium'    => 'array',
		'direction' => 'array',
		'theme'     => 'array',
		'color'     => 'array',
		'image'     => 'array'
	];

	public function getBuyableIdentifier( $options = null ) {
		return $this->id;
	}

	public function getBuyableDescription( $options = null ) {
		return $this->name;
	}

	public function getBuyablePrice( $options = null ) {
		return $this->price;
	}

	public function country() {
		return $this->belongsTo( Country::class );
	}

	public function user() {
		return $this->belongsTo( User::class );
	}

	public function favoredUsers() {
		return $this->belongsToMany( User::class, 'favorites' );
	}

	public function images() {
		return $this->belongsToMany( Media::class, 'artwork_images', 'artwork_id', 'media_id' );
	}

	public function image() {
		return $this->belongsTo( Media::class );
	}

	public function adds() {
		return $this->hasMany( Add::class );
	}

	public function statusString() {

		if ( $this->sold_at ) {
			return 'sold';
		}

		if ( ! $this->available ) {
			return 'temporarily-unavailable';
		}

		if ( $this->attributes['quantity'] ) {
			return 'available';
		}

		return 'unavailable';
	}

	public function availableInStockWithQuantity( $quantity = 1 ) {
		if ( $this->attributes['sold_at'] ) {
			return 'sold';
		} else if ( ! $this->attributes['available'] ) {
			return 'temporarily-unavailable';
		} else if ( $quantity <= $this->attributes['quantity'] ) {
			return 'available';
		} else {
			return 'unavailable';
		}
	}

	public function scopeAvailable( $query ) {
		return $query->where( 'available', true );
	}

	public function scopeQuantity( $query, $value = 1 ) {
		return $query->where( 'quantity', '>=', $value );
	}

	public function scopeNotSold( $query ) {
		return $query->whereNull( 'sold_at' );
	}

	public function scopeAuction( $query, $status = true ) {
		return $query->whereAuctionStatus( $status );
	}

	public function getOptionalSizeAttribute( $value ) {
		return ! ! $value;
	}

	public function getAuctionStatusAttribute( $value ) {
		return ! ! $value;
	}

	public function getAvailableAttribute( $value ) {
		return ! ! $value;
	}

	public function getFormattedPriceAttribute() {
		return currency( $this->attributes['price'], null, session( 'currency' ) );
	}

	public function getImageUrlAttribute() {

		if ( $this->image && file_exists( public_path( 'storage' . $this->image->url ) ) ) {
			return $this->image->url;
		} else {
			return '/no-image-placeholder.png';
		}
	}

	public function bids() {
		return $this->hasMany( Bid::class );
	}

	public function size() {
		return $this->width . ' x ' . $this->height . ' cm';
	}
}
