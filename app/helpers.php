<?php

if ( ! function_exists( 'getAllTranslations' ) ) {
	function getAllTranslations() {
		$translations = [];

		$groups = \DB::table( 'language_lines' )->distinct( 'group' )->pluck( 'group' );

		foreach ( $groups as $group ) {
			$translations[ $group ] = trans( $group );
		}

		return json_encode( $translations );
	}
}

if ( ! function_exists( 'showPage' ) ) {
	function showPage( $slug ) {

		$page = App\Page::whereSlug( $slug )->first();

		return $page->content;
	}
}

if ( ! function_exists( 'optionalPrice' ) ) {
	function optionalPrice( $price ) {

		if ( session( 'currency' ) !== currency()->config( 'default' ) ) {
			return ' / ~ ' . currency_format( $price, session( 'currency' ) );
		}

		return '';
	}
}

if ( ! function_exists( 'get_languages' ) ) {
	function get_languages() {
		$languages = \App\Language::active()->get();

		return $languages;
	}
}

function current_language() {
	if ( session( 'lang' ) ) {
		$language = \App\Language::whereCode( session( 'lang' ) )->first();
		if ( $language ) {
			return $language;
		}
	}

	// TODO discover language

	return \App\Language::first();
}

/**
 * @return bool
 */
if ( ! function_exists( 'is_rtl' ) ) {
	function is_rtl() {
		return ( current_language() && current_language()->is_rtl == 1 );
	}
}

if ( ! function_exists( 'paypal_ipn_verify' ) ) {
	function paypal_ipn_verify() {
		$paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
		if ( get_option( 'enable_paypal_sandbox' ) == 1 ) {
			$paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}

		// STEP 1: read POST data
		// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
		// Instead, read raw POST data from the input stream.
		$raw_post_data  = file_get_contents( 'php://input' );
		$raw_post_array = explode( '&', $raw_post_data );
		$myPost         = array();
		foreach ( $raw_post_array as $keyval ) {
			$keyval = explode( '=', $keyval );
			if ( count( $keyval ) == 2 ) {
				$myPost[ $keyval[0] ] = urldecode( $keyval[1] );
			}
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if ( function_exists( 'get_magic_quotes_gpc' ) ) {
			$get_magic_quotes_exists = true;
		}
		foreach ( $myPost as $key => $value ) {
			if ( $get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1 ) {
				$value = urlencode( stripslashes( $value ) );
			} else {
				$value = urlencode( $value );
			}
			$req .= "&$key=$value";
		}

		// STEP 2: POST IPN data back to PayPal to validate
		$ch = curl_init( $paypal_action_url );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) );

		if ( ! ( $res = curl_exec( $ch ) ) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close( $ch );
			exit;
		}
		curl_close( $ch );

		// STEP 3: Inspect IPN validation result and act accordingly
		if ( strcmp( $res, "VERIFIED" ) == 0 ) {
			return true;
		} else if ( strcmp( $res, "INVALID" ) == 0 ) {
			return false;
		}
	}
}
