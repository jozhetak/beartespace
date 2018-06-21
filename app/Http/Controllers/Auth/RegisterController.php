<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Jobs\SendVerificationEmail;


class RegisterController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function register( Request $request ) {
		$this->validator( $request->all() )->validate();

//        if (get_option('enable_recaptcha_registration') == 1){
//            $this->validate($request, array('g-recaptcha-response' => 'required'));
//
//            $secret = get_option('recaptcha_secret_key');
//            $gRecaptchaResponse = $request->input('g-recaptcha-response');
//            $remoteIp = $request->ip();
//
//            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
//            $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
//            if ( ! $resp->isSuccess()) {
//                return redirect()->back()->with('error', 'reCAPTCHA is not verified');
//            }
//
//        }

		event( new Registered( $user = $this->create( $request->all() ) ) );

		dispatch( new SendVerificationEmail( $user ) );

		return view( 'auth.verify' );

//        return $this->registered($request, $user)
//            ?: redirect($this->redirectPath());
	}

	public function verify( $token ) {
		$user = User::where( 'email_token', $token )->first();

		if ( $user ) {
			$user->email_verified = true;
			$user->save();

			$this->guard()->login( $user );

			return view( 'auth.confirm', [ 'user' => $user ] );
		} else {
			return redirect( '/register' );
		}

	}

	public function confirm() {

	}

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'guest' );
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator( array $data ) {
		return Validator::make( $data, [
			'name'     => 'required|string|max:255',
			'email'    => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
		] );
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	protected function create( array $data ) {
		return User::create( [
			'name'          => $data['name'],
			'email'         => $data['email'],
			'email_token'   => base64_encode( $data['email'] ),
			'password'      => bcrypt( $data['password'] ),
			// TODO change user type from request
			'user_type'     => 'user',
			'active_status' => '1'
		] );
	}
}
