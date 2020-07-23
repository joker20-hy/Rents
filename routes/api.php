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
		'namespace' => 'Suggest',
		'prefix' => 'sg'
	], function () {
		Route::get('provinces', 'ProvinceController@main');
		Route::get('districts', 'DistrictController@main');
		Route::get('areas', 'AreaController@main');
		Route::get('houses', 'HouseController@main');
		Route::get('address', 'AddressController@main');
	});
	Route::group([
		'namespace' => 'District',
		'prefix' => 'district'
	], function () {
		Route::get('list/{provinceId?}', 'ListController@main')->middleware('auth:api');
		Route::get('{provinceId?}', 'IndexController@main');
	});
	Route::group([
		'namespace' => 'House',
		'prefix' => 'house'
	], function () {
		Route::get('', 'IndexController@main');
		Route::get('list', 'ListController@main')->middleware(['auth:api', 'admin-owner-role']);
		Route::get('{id}', 'ShowController@main');
	});
	Route::group([
		'namespace' => 'Room',
		'prefix' => 'room'
	], function () {
		Route::get('', 'IndexController@main');
		Route::get('list', 'ListController@main')->middleware(['auth:api', 'admin-owner-role']);
		Route::get('{id}', 'ShowController@main');
	});
	Route::group([
		'namespace' => 'Criteria',
		'prefix' => 'criteria'
	], function () {
		Route::get('', 'IndexController@main');
	});
	Route::group([
		'namespace' => 'Review',
		'prefix' => 'review'
	], function () {
		Route::post('{type}', 'StoreController@main')->middleware('auth:api');
	});
	Route::group([
	    'middleware' => 'auth:api'
	], function() {
		Route::post('refresh', 'Auth\RefreshController@main');
		Route::post('logout', 'Auth\LoginController@logout');
		Route::group([
			'namespace' => 'Province',
			'prefix' => 'province'
		], function () {
			Route::get('{perpage?}', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'District',
			'prefix' => 'district'
		], function () {
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'Area',
			'prefix' => 'area'
		], function () {
			Route::get('{type?}/{id?}', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'User',
			'prefix' => 'user'
		], function () {
			Route::get('', 'IndexController@main');
			Route::get('find/{id?}', 'ShowControler@main');
			Route::get('{id}/avatar', 'UpdateAvatarController@main');
			Route::get('{id}/profile', 'ShowProfileController@main');
			Route::put('{id}/profile', 'UpdateProfileController@main');
			Route::get('{id}/setting', 'ShowSettingController@main');
			Route::put('{id}/setting', 'UpdateSettingController@main');
			Route::put('{id}/verify', 'VerifyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'Direction',
			'prefix' => 'direction'
		], function () {
			Route::get('', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'House',
			'prefix' => 'house'
		], function () {
			Route::post('', 'StoreController@main')->middleware('admin-owner-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-owner-role');
			Route::post('{id}/images', 'UploadImagesController@main')->middleware('admin-owner-role');
			Route::put('{id}/images', 'UpdateImagesController@main')->middleware('admin-owner-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-owner-role');
		});
		Route::group([
			'namespace' => 'Room',
			'prefix' => 'room'
		], function () {
			Route::post('', 'StoreController@main')->middleware('admin-owner-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-owner-role');
			Route::post('{id}/images', 'UploadImagesController@main')->middleware('admin-owner-role');
			Route::put('{id}/images', 'UpdateImagesController@main')->middleware('admin-owner-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-owner-role');
		});
		Route::group([
			'namespace' => 'Service',
			'prefix' => 'service'
		], function () {
			Route::get('', 'IndexController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'Criteria',
			'prefix' => 'criteria'
		], function () {
			Route::get('list', 'ListController@main');
			Route::post('', 'StoreController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Review',
			'prefix' => 'review'
		], function () {
			Route::get('{type}', 'IndexController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Image',
			'prefix' => 'image'
		], function () {
			Route::post('', 'StoreController@main');
		});
	});
});
