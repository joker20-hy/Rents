<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Api'], function() {
	Route::group(['namespace' => 'Auth'], function() {
		Route::post('/login', 'LoginController@login');
	});

	Route::group([
	    'middleware' => 'auth:api'
	], function() {
		Route::post('/refresh', 'Auth\RefreshController@main');
		Route::post('/logout', 'Auth\LoginController@logout');
		/**
		 * 
		 */
		Route::group([
			'namespace' => 'Province',
			'prefix' => 'province'
		], function () {
			Route::get('{perpage?}', 'IndexController@main');
			Route::post('', 'CreateController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		/**
		 * 
		 */
		Route::group([
			'namespace' => 'District',
			'prefix' => 'district'
		], function () {
			Route::get('', 'IndexController@main');
			Route::post('', 'CreateController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
	});
});
