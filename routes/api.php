<?php
	use Illuminate\Http\Request ;
	use Illuminate\Support\Facades\Input;
//	use Symfony\Component\Console\Input\Input;

//	$request->has('name');

	/*
		|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| is assigned the "api" middleware group. Enjoy building your API!
	|
	*/

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});
//	Route::group(['namespace' => 'api'], function () {
//
//		Route::post('/login', 'UserController@login');
//		Route::post('/details', 'UserController@details');
//		Route::resource ('users', 'UserController');
//
//
////		Route::get('api/{phone?}', 'UserController@get_phone');
//
////		Route::get('users','UserController@get_phone' );
//
////		Route::get('users/{p/*hone?}', [
////			'as'   => 'phone',
////			'uses' => 'UserController@get_phone'
////		]);
//
//
//
//
//
////		Route::get ('users', 'UserController@get_phone');
////		Route::get('users{phone?}',  'UserController@get_phone');
////		Route::resource('payments', 'PaymentsController', ['except' => 'create']);
////		Route::get('users/{?}', 'UserController@get_phone');
////		Route::get('users/{id}, array('as);
////			Route::get ('users/{phone?}', 'UserController@get_phone',function ($p)
////			{
////				dd('asdasd');
////			});
////			Route::get ('users/{phone}?', 'UserController@get_phone');
//
////			Route::get ('users/{phone?}', 'UserController@get_phone');
////		else if ($request->has('start_date') or $request->has('end_date') or $request->has('status'))
////			Route::get ('users', 'UserController@get_date');
////		else if ($request->has ('date'))
////			Route::get ('users', 'UserController@get_user_by_date');
////
//////
//	});

//Route::get('api/users/{phone?}', 'UserController@get_phone');

//	Route::get('{phone?}','api\UserController@get_phone');
		Route::group(['namespace' => 'api'], function () {

			Route::post ('users/login', 'UserController@login');
			Route::post ('users/details', 'UserController@details');
			Route::resource ('users', 'UserController');

			if(	Input::has ('phone'))
				Route::get ('users/', 'UserController@get_phone');
			else if (Input::has('start_date') or Input::has('end_date') or Input::has('status'))
			Route::get ('users', 'UserController@get_date');
		else if (Input::has ('date'))
			Route::get ('users', 'UserController@get_user_by_date');
		else
			Route::resource ('users', 'UserController');
		});