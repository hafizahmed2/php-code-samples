<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('admin/signup','AdminController@signup');
Route::post('admin/login','AdminController@login');

// Route::post('user/signup','UserController@signup');
Route::post('user/login','UserController@login');

// Route::post('bloger/signup','BlogerController@signup');
Route::post('bloger/login','BlogerController@login');

Route::group(['middleware' => ['auth:admin-api','admin'],'prefix' => 'admin'], function () {

	Route::resource('users','UserController');
	Route::resource('blogers','BlogerController');

	});

Route::group(['middleware' => ['auth:user-api','user'],'prefix' => 'user'], function () {

	Route::resource('blogers','BlogerController');

	});

Route::group(['middleware' => ['auth:bloger-api','bloger'],'prefix' => 'bloger'], function () {

	Route::resource('blogs','BlogController');


	});

