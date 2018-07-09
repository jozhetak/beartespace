<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'web'], function () {

	Auth::routes();

	Route::get( '/','HomeController@index' )->name('home');
	Route::get( '/auction','HomeController@auctions' )->name('auctions');
	Route::get( '/auction/{id}','HomeController@auctions' )->name('auction');
	Route::get( '/artwork','HomeController@artworks' )->name('artworks');
	Route::get( '/artwork/{id}', 'HomeController@artwork' )->name('artwork');
	Route::get( '/artist','HomeController@artists' )->name('artists');
	Route::get( '/artist/{id}','HomeController@artist' )->name('artist');

	// Contact us page
	Route::get( 'contact-form', 'HomeController@contactForm')->name('contact-form');
	Route::post( 'contact-form', 'HomeController@contactFormPost')->name('contact-form');

	// Search
	Route::post( 'search/{query?}', 'HomeController@search')->name('search');

	// Leads
	Route::post('add-lead', 'LeadController@addLead')->name('add-lead');

	Route::get( '/language/{lang}', [ 'as' => 'switch_language', 'uses' => 'LanguageController@switchLang' ] );

	// Shopping
	Route::get('shopping-cart', 'HomeController@shoppingCart')->name('shopping-cart');
	Route::get('add-to-cart/{id}', 'ArtworkController@addToCart')->name('add-to-cart');
	Route::get('toggle-to-cart/{id}', 'ArtworkController@toggleToCart')->name('toggle-to-cart');
	Route::get('remove-from-cart/{id}', 'ArtworkController@removeFromCart')->name('remove-from-cart');
	Route::get( 'checkout', 'HomeController@checkout' )->name('checkout');


// Pages
	Route::get( 'about', 'HomeController@about' )->name( 'about' );
	Route::get( 'rules', 'HomeController@rules' )->name( 'rules' );
	Route::get( 'shipping', 'HomeController@shipping' )->name( 'shipping' );


//Dashboard Route
	Route::group( [ 'prefix' => 'dashboard', 'middleware' => 'dashboard' ], function () {

		// All users access
		Route::get( '/', 'DashboardController@dashboard' )->name( 'dashboard' );
		Route::get( 'profile', 'UserController@profile' )->name( 'dashboard.profile' );
		Route::get( 'change-password', 'UserController@changePassword' )->name( 'change-password' );
		Route::post( 'change-password', 'UserController@changePasswordPost' );
		Route::get( 'payments', 'PaymentController@index' )->name( 'payments' );


		Route::get( 'favorites', 'UserController@favoriteArtworks' )->name( 'favorites' );


		// Not user (admin, artist, gallery)
		Route::group( [ 'middleware' => 'artist' ], function () {

			Route::get( 'artworks', 'ArtworkController@index' )->name( 'dashboard.artworks' );
			Route::get( 'artwork/create', 'ArtworkController@create' )->name( 'dashboard.artwork.create' );
			Route::get( 'artwork/{id}/edit', 'ArtworkController@edit' )->name( 'dashboard.artwork.edit' );
			Route::post( 'artwork/{id}', 'ArtworkController@destroy' )->name( 'dashboard.artwork.destroy' );
		} );


		// Admin only

		Route::group( [ 'middleware' => 'admin' ], function () {

			Route::get( 'users', 'UserController@index' )->name( 'admin.users' );
			Route::get( 'translations', 'TranslationController@index' )->name( 'admin.translations' );
			Route::get( 'languages', 'LanguageController@index' )->name( 'admin.languages' );
			Route::get( 'pages','PageController@index')->name('admin.pages');
			Route::get( 'messages', 'MessageController@messages')->name('admin.messages');


			Route::group( [ 'prefix' => 'settings' ], function () {

				Route::get( 'theme-settings', [ 'as' => 'theme_settings', 'uses' => 'SettingsController@ThemeSettings' ] );
				Route::get( 'modern-theme-settings', [
					'as'   => 'modern_theme_settings',
					'uses' => 'SettingsController@modernThemeSettings'
				] );
				Route::get( 'social-url-settings', [
					'as'   => 'social_url_settings',
					'uses' => 'SettingsController@SocialUrlSettings'
				] );
				Route::get( 'general', [ 'as' => 'general_settings', 'uses' => 'SettingsController@GeneralSettings' ] );
				Route::get( 'payments', [ 'as' => 'payment_settings', 'uses' => 'SettingsController@PaymentSettings' ] );
				Route::get( 'ad', [ 'as' => 'ad_settings', 'uses' => 'SettingsController@AdSettings' ] );

				Route::get( 'storage', [
					'as'   => 'file_storage_settings',
					'uses' => 'SettingsController@StorageSettings'
				] );
				Route::get( 'social', [ 'as' => 'social_settings', 'uses' => 'SettingsController@SocialSettings' ] );
				Route::get( 'blog', [ 'as' => 'blog_settings', 'uses' => 'SettingsController@BlogSettings' ] );
				Route::get( 'other', [ 'as' => 'other_settings', 'uses' => 'SettingsController@OtherSettings' ] );
				Route::post( 'other', [ 'as' => 'other_settings', 'uses' => 'SettingsController@OtherSettingsPost' ] );

				Route::get( 'recaptcha', [
					'as'   => 're_captcha_settings',
					'uses' => 'SettingsController@reCaptchaSettings'
				] );

				//Save settings / options
				Route::post( 'save-settings', [ 'as' => 'save_settings', 'uses' => 'SettingsController@update' ] );
				Route::get( 'monetization', [ 'as' => 'monetization', 'uses' => 'SettingsController@monetization' ] );
			} );


			Route::group( [ 'prefix' => 'posts' ], function () {
				Route::get( '/', [ 'as' => 'posts', 'uses' => 'PostController@posts' ] );
				Route::get( 'data', [ 'as' => 'posts_data', 'uses' => 'PostController@postsData' ] );

				Route::get( 'create', [ 'as' => 'create_new_post', 'uses' => 'PostController@createPost' ] );
				Route::post( 'create', [ 'uses' => 'PostController@storePost' ] );
				Route::post( 'delete', [ 'as' => 'delete_post', 'uses' => 'PostController@destroyPost' ] );

				Route::get( 'edit/{slug}', [ 'as' => 'edit_post', 'uses' => 'PostController@editPost' ] );
				Route::post( 'edit/{slug}', [ 'uses' => 'PostController@updatePost' ] );
			} );

			Route::group( [ 'prefix' => 'pages' ], function () {
				Route::get( 'data', [ 'as' => 'pages_data', 'uses' => 'PostController@pagesData' ] );

				Route::get( 'create', [ 'as' => 'create_new_page', 'uses' => 'PostController@create' ] );
				Route::post( 'create', [ 'uses' => 'PostController@store' ] );
				Route::post( 'delete', [ 'as' => 'delete_page', 'uses' => 'PostController@destroy' ] );

				Route::get( 'edit/{slug}', [ 'as' => 'edit_page', 'uses' => 'PostController@edit' ] );
				Route::post( 'edit/{slug}', [ 'uses' => 'PostController@updatePage' ] );
			} );

			Route::group( [ 'prefix' => 'admin_comments' ], function () {
				Route::get( '/', [ 'as' => 'admin_comments', 'uses' => 'CommentController@index' ] );
				Route::get( 'data', [ 'as' => 'admin_comments_data', 'uses' => 'CommentController@commentData' ] );

				Route::post( 'action', [ 'as' => 'comment_action', 'uses' => 'CommentController@commentAction' ] );
			} );

			Route::get( 'approved', [ 'as' => 'approved_ads', 'uses' => 'ArtworkController@index' ] );
			Route::get( 'pending', [ 'as' => 'admin_pending_ads', 'uses' => 'ArtworkController@adminPendingAds' ] );
			Route::get( 'blocked', [ 'as' => 'admin_blocked_ads', 'uses' => 'ArtworkController@adminBlockedAds' ] );
			Route::post( 'status-change', [ 'as' => 'ads_status_change', 'uses' => 'ArtworkController@adStatusChange' ] );

			Route::get( 'ad-reports', [ 'as' => 'ad_reports', 'uses' => 'ArtworkController@reports' ] );
			Route::get( 'users-data', [ 'as' => 'get_users_data', 'uses' => 'UserController@usersData' ] );
			Route::get( 'users-info/{id}', [ 'as' => 'user_info', 'uses' => 'UserController@userInfo' ] );
			Route::post( 'change-user-status', [ 'as' => 'change_user_status', 'uses' => 'UserController@changeStatus' ] );
			Route::post( 'change-user-feature', [
				'as'   => 'change_user_feature',
				'uses' => 'UserController@changeFeature'
			] );
			Route::post( 'delete-reports', [ 'as' => 'delete_report', 'uses' => 'ArtworkController@deleteReports' ] );


			Route::group( [ 'prefix' => 'administrators' ], function () {
				Route::get( '/', [ 'as' => 'administrators', 'uses' => 'UserController@administrators' ] );
				Route::get( 'create', [ 'as' => 'add_administrator', 'uses' => 'UserController@addAdministrator' ] );
				Route::post( 'create', [ 'uses' => 'UserController@storeAdministrator' ] );

				Route::post( 'block-unblock', [
					'as'   => 'administratorBlockUnblock',
					'uses' => 'UserController@administratorBlockUnblock'
				] );

			} );

		} );

		Route::group( [ 'prefix' => 'u' ], function () {
			Route::group( [ 'prefix' => 'posts' ], function () {
				Route::post( 'delete', [ 'as' => 'delete_ads', 'uses' => 'ArtworkController@destroy' ] );
				Route::get( 'edit/{id}', [ 'as' => 'edit_ad', 'uses' => 'ArtworkController@edit' ] );
				Route::post( 'edit/{id}', [ 'uses' => 'ArtworkController@update' ] );
				Route::get( 'my-lists', [ 'as' => 'my_ads', 'uses' => 'ArtworkController@myAds' ] );
				//Upload ads image
				Route::post( 'upload-a-image', [
					'as'   => 'upload_ads_image',
					'uses' => 'ArtworkController@uploadAdsImage'
				] );
				Route::post( 'upload-post-image', [
					'as'   => 'upload_post_image',
					'uses' => 'PostController@uploadPostImage'
				] );
				//Delete media
				Route::post( 'delete-media', [ 'as' => 'delete_media', 'uses' => 'ArtworkController@deleteMedia' ] );
				Route::post( 'feature-media-creating', [
					'as'   => 'feature_media_creating_ads',
					'uses' => 'ArtworkController@featureMediaCreatingAds'
				] );
				Route::get( 'append-media-image', [
					'as'   => 'append_media_image',
					'uses' => 'ArtworkController@appendMediaImage'
				] );
				Route::get( 'append-post-media-image', [
					'as'   => 'append_post_media_image',
					'uses' => 'PostController@appendPostMediaImage'
				] );
				Route::get( 'pending-lists', [ 'as' => 'pending_ads', 'uses' => 'ArtworkController@pendingAds' ] );
				Route::get( 'archive-lists', [ 'as' => 'favourite_ad', 'uses' => 'ArtworkController@create' ] );

				Route::get( 'reports-by/{slug}', [ 'as' => 'reports_by_ads', 'uses' => 'ArtworkController@reportsByAds' ] );

				//bids
				Route::get( 'bids/{ad_id}', [ 'as' => 'auction_bids', 'uses' => 'BidController@index' ] );
				Route::post( 'bids/action', [ 'as' => 'bid_action', 'uses' => 'BidController@bidAction' ] );
				Route::get( 'bidder_info/{bid_id}', [ 'as' => 'bidder_info', 'uses' => 'BidController@bidderInfo' ] );
			} );
		} );

	} );

});








//Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');





Route::get( 'page/{slug}', [ 'as' => 'single_page', 'uses' => 'PostController@showPage' ] );

Route::get( 'category/{cat_id?}', [ 'uses' => 'CategoriesController@show' ] )->name( 'category' );




Route::get( 'auction-by-user/{id?}', [ 'as' => 'ads_by_user', 'uses' => 'ArtworkController@adsByUser' ] );

Route::get( 'auction/{id}/{slug?}', [ 'as' => 'single_ad', 'uses' => 'ArtworkController@singleAuction' ] );
Route::get( 'embedded/{slug}', [ 'as' => 'embedded_ad', 'uses' => 'ArtworkController@embeddedAd' ] );

Route::post( 'save-ad-as-favorite', [ 'as' => 'save_ad_as_favorite', 'uses' => 'UserController@saveAdAsFavorite' ] );
Route::post( 'report-post', [ 'as' => 'report_ads_pos', 'uses' => 'ArtworkController@reportAds' ] );
Route::post( 'reply-by-email', [ 'as' => 'reply_by_email_post', 'uses' => 'UserController@replyByEmailPost' ] );
Route::post( 'post-comments/{id}', [ 'as' => 'post_comments', 'uses' => 'CommentController@postComments' ] );


Route::get( 'apply_job', function () {
	return redirect( route( 'home' ) );
} );

// Password reset routes...
//Route::post('send-password-reset-link', ['as' => 'send_reset_link', 'uses'=>'Auth\PasswordController@postEmail']);
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', ['as'=>'password_reset_post', 'uses'=>'Auth\PasswordController@postReset']);

Route::post( 'get-sub-category-by-category', [
	'as'   => 'get_sub_category_by_category',
	'uses' => 'ArtworkController@getSubCategoryByCategory'
] );
Route::post( 'get-brand-by-category', [
	'as'   => 'get_brand_by_category',
	'uses' => 'ArtworkController@getBrandByCategory'
] );
Route::post( 'get-category-info', [
	'as'   => 'get_category_info',
	'uses' => 'ArtworkController@getParentCategoryInfo'
] );
Route::post( 'get-state-by-country', [
	'as'   => 'get_state_by_country',
	'uses' => 'ArtworkController@getStateByCountry'
] );
Route::post( 'get-city-by-state', [ 'as' => 'get_city_by_state', 'uses' => 'ArtworkController@getCityByState' ] );
Route::post( 'switch/product-view', [
	'as'   => 'switch_grid_list_view',
	'uses' => 'ArtworkController@switchGridListView'
] );


//Post bid
Route::post( '{id}/post-new', [ 'as' => 'post_bid', 'uses' => 'BidController@postBid' ] );


//Checkout payment
Route::get( 'checkout/{transaction_id}', [ 'as' => 'payment_checkout', 'uses' => 'PaymentController@checkout' ] );
Route::post( 'checkout/{transaction_id}', [ 'uses' => 'PaymentController@chargePayment' ] );
//Payment success url
Route::any( 'checkout/{transaction_id}/payment-success', [
	'as'   => 'payment_success_url',
	'uses' => 'PaymentController@paymentSuccess'
] );
Route::any( 'checkout/{transaction_id}/paypal-notify', [
	'as'   => 'paypal_notify_url',
	'uses' => 'PaymentController@paypalNotify'
] );


Route::group( [ 'prefix' => 'login' ], function () {
	//Social login route

	Route::get( 'facebook', [ 'as' => 'facebook_redirect', 'uses' => 'SocialLogin@redirectFacebook' ] );
	Route::get( 'facebook-callback', [ 'as' => 'facebook_callback', 'uses' => 'SocialLogin@callbackFacebook' ] );

	Route::get( 'google', [ 'as' => 'google_redirect', 'uses' => 'SocialLogin@redirectGoogle' ] );
	Route::get( 'google-callback', [ 'as' => 'google_callback', 'uses' => 'SocialLogin@callbackGoogle' ] );

	Route::get( 'twitter', [ 'as' => 'twitter_redirect', 'uses' => 'SocialLogin@redirectTwitter' ] );
	Route::get( 'twitter-callback', [ 'as' => 'twitter_callback', 'uses' => 'SocialLogin@callbackTwitter' ] );
} );
