<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Artwork;

class CartController extends Controller {

	public $response;

	public function index() {
//		dd( Cart::content());
		return view( 'cart.index' );
	}

	public function apiToggleCart( Request $request, $id ) {

		$artwork = Artwork::find( $id );

		$cart = Cart::content();

		if ( $cart->contains( 'id', $artwork->id ) ) {
			$cart->map( function ( $item, $rowId ) use ( $artwork ) {
				if ( $artwork->id == $item->id ) {
					Cart::remove( $rowId );

					$this->response = [
						'status'  => 'success',
						'message' => 'Artwork Removed from <a href="/cart">Shopping Cart</a>',
						'data'    => Cart::content()->values()
					];
				}
			} );
		} else {
//			Cart::add( $artwork->id, $artwork->name, 1, $artwork->price );
			Cart::add( $artwork );

			$this->response = [
				'status'  => 'success',
				'message' => 'Artwork Added to <a href="/cart">Shopping Cart</a>',
				'data'    => Cart::content()->values()
			];
		}

		if ( auth()->user() ) {

//			Cart::restore( auth()->user()->id );
		}

		return $this->response;
	}


	public function buyNow( Request $request, $id ) {

		$artwork = Artwork::findOrFail( $id );

		$cart = Cart::content();

		if ( $cart->contains( 'id', $artwork->id ) ) {
			$cart->map( function ( $item, $rowId ) use ( $artwork ) {
				if ( $artwork->id == $item->id ) {
					$this->response = redirect()->route( 'cart' );
				}
			} );
		} else {
			Cart::add( $artwork );

			$this->response = redirect()->route( 'checkout' );

		}

		return $this->response;
	}

	public function addItem( Request $request, $id ) {
		$artwork = Artwork::findOrFail( $id );

		if ( ! Cart::content()->contains( 'id', $artwork->id ) ) {
			Cart::add( $artwork );
		}

		return redirect()->route( 'cart' );
	}

	public function removeItem( Request $request, $id ) {
		$artwork = Artwork::findOrFail( $id );

		if ( Cart::content()->contains( 'id', $artwork->id ) ) {
			$item = Cart::content()->where( 'id', $artwork->id )->first();

			Cart::remove( $item->rowId );
		}

		return redirect()->route('cart');

		return redirect()->back()->with( 'message', [
			'status'  => 'success',
			'message' => $artwork->name . ' removed from shopping cart'
		] );
	}

}
