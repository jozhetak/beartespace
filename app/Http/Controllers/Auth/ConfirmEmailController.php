<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ConfirmEmailController extends Controller
{
	public function verify() {

		$user = auth()->user();

		return view( 'auth.confirm', compact('user'));
	}

	public function confirm( $token ) {

		$user = User::where( 'activation_token', $token )->first();

		if ( ! $user ) {
			return redirect()->route('register');
		}

		$user->email_verified = true;

		$user->save();

		return redirect()->route('login');
	}

	public function resend() {

		$user = auth()->user();

		$user->notify( new SignupActivate( $user ) );

		return redirect()->route('confirm-email.verify');

	}
}