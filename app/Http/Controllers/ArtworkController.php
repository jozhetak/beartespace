<?php

namespace App\Http\Controllers;

use App\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class ArtworkController extends Controller {


	public function artworks( Request $request ) {

		$artworks = Artwork::query();

		if ( $request->input( 'artist' ) ) {
			$artist = $request->input( 'artist' );

			$artworks->with( 'user' )->whereHas( 'user', function ( $user ) use ( $artist ) {
				$userNameArray = explode( ' ', $artist );
				foreach ( $userNameArray as $userNamePart ) {
					$user->whereRaw( 'LOWER(first_name) LIKE ?', '%' . $userNamePart . '%' )
					     ->orWhereRaw( 'LOWER(last_name) LIKE ?', '%' . $userNamePart . '%' );
				}
			} );
		}

		if ( $request->input( 'artwork' ) ) {
			$artwork = $request->input( 'artwork' );
			$artworks->whereRaw( 'LOWER(name) LIKE ?', '%' . $artwork . '%' );
		}

		if ( $request->input( 'category' ) ) {
			$queries = explode( ',', $request->input( 'category' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(category) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'medium' ) ) {
			$queries = explode( ',', $request->input( 'medium' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(medium) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'direction' ) ) {
			$queries = explode( ',', $request->input( 'direction' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(direction) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'theme' ) ) {
			$queries = explode( ',', $request->input( 'theme' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(theme) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'country' ) ) {
			$countries = explode( ',', $request->input( 'country' ) );

			$artworks->with( 'user' )->whereHas( 'user', function ( $user ) use ( $countries ) {
				foreach ( $countries as $country ) {
					$user->where( 'country_id', $country );
				}
			} );
		}

		if ( $request->input( 'shape' ) ) {
			$queries = explode( ',', $request->input( 'shape' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(shape) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'color' ) ) {
			$queries = explode( ',', $request->input( 'color' ) );
			foreach ( $queries as $query ) {
				$artworks->whereRaw( 'LOWER(color) LIKE ?', '%' . $query . '%' );
			}
		}

		if ( $request->input( 'price_min' ) ) {
			$priceMin = currency($request->input( 'price_min' ), session('currency'), 'EUR',false);
			$artworks->where( 'price', '>=', $priceMin );
		}

		if ( $request->input( 'price_max' ) ) {
			$priceMax = currency($request->input( 'price_max' ), session('currency'), 'EUR',false);
			$artworks->where( 'price', '<=', $priceMax );
		}

//		foreach ( $artworks->get() as $artwork ) {
//			dump($artwork->price);
//			dump($artwork->currency);
//			dump( $artwork->price < $priceMax );
//			dump( $artwork->price > $priceMin );
//		}

		$items = 15;

		if ( $request->has( 'items' ) && $request->input( 'items' ) > 1 ) {
			$items = $request->get( 'items' );
		}

		$artworks = $artworks->notSold()->available()->with( 'images', 'user.country' )->paginate( $items );

		return view( 'artwork.index', compact( 'artworks' ) );
	}

	public function artwork( $id ) {

		$artwork = Artwork::find( $id )->load('images');

		return view( 'artwork.show', compact( 'artwork' ) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$title = 'My artworks';

		$order = auth()->user()->orders()->first();

//		return Cart::content();
//		return new \App\Mail\OrderPaid($order);
//		dd($order->content);
//

//
//		Mail::to( $artwork->user )->send( new OrderPaid( $order ) );

		$user = Auth::user();

		$artworks = $user->artworks()->orderBy( 'id', 'desc' )->with( 'images' )->get();

		return view( 'dashboard.artworks.index', compact( 'title', 'artworks' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$title = 'Upload New Artwork';

		return view( 'dashboard.artworks.create', compact( 'title' ) );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( $id ) {

		$title = 'Edit Artwork';

		$artwork = Artwork::whereId( $id )->with( 'images' )->firstOrFail();

//		return $artwork;
		$user = auth()->user();

//		return $artwork;

		if ( $artwork->user_id === $user->id || $user->isAdmin() ) {
			return view( 'dashboard.artworks.edit', compact( 'title', 'artwork' ) );
		} else {
			return redirect( route( 'dashboard' ) )->with( 'error', trans( 'app.access_restricted' ) );
		}

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( Request $request, $id ) {
		$artwork = Artwork::find( $id );

		$artwork->images()->delete();
		$artwork->delete();
	}
}
