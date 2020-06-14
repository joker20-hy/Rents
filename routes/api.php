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
		Route::post('login', 'LoginController@login');
	});

	Route::group([
	    'middleware' => 'auth:api'
	], function() {
		Route::post('refresh', 'Auth\RefreshController@main');
		Route::post('logout', 'Auth\LoginController@logout');
		/**
		 * 
		 */
		Route::group([
			'namespace' => 'Province',
			'prefix' => 'province'
		], function () {
			Route::get('{perpage?}', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
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
			Route::get('{provinceId?}', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		/**
		 * 
		 */
		Route::group([
			'namespace' => 'Area',
			'prefix' => 'area'
		], function () {
			Route::get('{type?}/{id?}', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		/**
		 * 
		 */
		Route::group([
			'namespace' => 'Suggest',
			'prefix' => 'sg'
		], function () {
			Route::get('provinces', 'ProvinceController@main');
			Route::get('districts', 'DistrictController@main');
			Route::get('areas', 'AreaController@main');
		});
		Route::group([
			'namespace' => 'User',
			'prefix' => 'user'
		], function () {
			Route::get('', 'IndexController@main');
			Route::get('find/{userId?}', 'ShowControler@main');
			// Route::post('', '');
			// Route::put('', '');
			// Route::delete('', '');
			Route::get('{userId}/profile', 'ShowProfileController@main');
			Route::put('{userId}/profile', 'UpdateProfileController@main');
			Route::get('{userId}/setting', 'ShowSettingController@main');
			Route::put('{userId}/setting', 'UpdateSettingController@main');
			Route::put('{userId}/verify', 'VerifyController@main')->middleware('admin-role');
		});

		Route::group([
			'namespace' => 'Direction',
			'prefix' => 'direction'
		], function () {
			Route::get('', 'IndexController@main');
			Route::post('', 'StoreController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'House',
			'prefix' => 'house'
		], function () {
			Route::get('', 'IndexController@main');
			Route::post('', 'StoreController@main');
			Route::post('{id}', 'UpdateController@main');
			Route::post('{id}/images', 'UploadImagesController@main');
			Route::put('{id}/images', 'UpdateImagesController@main');
		});

		Route::group([
			'namespace' => 'Image',
			'prefix' => 'image'
		], function () {
			Route::post('{folder_type?}', 'StoreController@main');
		});
	});
});
