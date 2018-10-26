<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class HasSellerAccess {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle( $request, Closure $next ) {
		if ( ! Auth::check() ) {
			return redirect()->guest( route( 'login' ) )->with( 'error', trans( 'app.unauthorized_access' ) );
		}

		$user = Auth::user();

		if ( $user->isUser() ) {
			return redirect( route( 'dashboard' ) )->with( 'error', trans( 'app.access_restricted' ) );
		}

		return $next( $request );
	}
}