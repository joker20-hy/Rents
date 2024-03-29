<?php

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
		Route::post('register', 'RegisterController@main');
		Route::get('verify', 'SendVerifyController@main');
		Route::post('verify/{id}', 'VerifyController@main');
		Route::put('change-password/{id}', 'UpdatePasswordController@main');
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
		Route::get('list/{provinceId?}', 'ListController@main')->middleware(['auth:api']);
		Route::get('{provinceId?}', 'IndexController@main');
	});
	Route::group([
		'namespace' => 'House',
		'prefix' => 'house'
	], function () {
		Route::get('', 'IndexController@main');
		Route::get('list', 'ListController@main')->middleware(['auth:api', 'admin-owner-role']);
		Route::get('{id}/services', 'ServicesController@main');
		Route::get('{id}', 'ShowController@main');
	});
	Route::group([
		'namespace' => 'Room',
		'prefix' => 'room'
	], function () {
		Route::group([
			'namespace' => 'Review',
		], function () {
			Route::get('{id}/review', 'IndexController@main');
			Route::post('{id}/review', 'StoreController@main')->middleware(['auth:api']);
		});
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
		Route::get('{type}', 'IndexController@main');
		Route::post('{type}', 'StoreController@main')->middleware(['auth:api']);
	});

	Route::group([
	    'middleware' => ['auth:api']
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
			Route::get('room', 'RoomController@main')->middleware('renter-role');
			Route::get('auth/{id?}', 'ShowControler@main');
			Route::post('{id}/avatar', 'UpdateAvatarController@main');
			Route::get('{id}/profile', 'ShowProfileController@main');
			Route::put('{id}/profile', 'UpdateProfileController@main');
			Route::get('{id}/setting', 'ShowSettingController@main');
			Route::put('{id}/setting', 'UpdateSettingController@main');
			Route::put('{id}/verify', 'VerifyController@main')->middleware('admin-role');
			Route::get('{id}', 'ShowControler@main');
			Route::put('leave-room', 'LeaveRoomController@main')->middleware('owner-renter-role');
			Route::put('{id}', 'UpdateController@main');
			Route::post('rent-room/{room_id}', 'RentRoomController@main');
			Route::post('{id}/apply-for-owner', 'ApplyForOwnerController@main');
		});
		Route::group([
			'namespace' => 'Owner',
			'prefix' => 'owner'
		], function () {
			Route::group([
				'prefix' => 'application',
				'middleware' => 'admin-role'
			], function () {
				Route::get('list', 'ListApplicationController@main');
				Route::put('{id}/approve', 'ApproveApplicationController@main');
				Route::put('{id}/decline', 'DeclineApplicationController@main');
			});
			Route::group([
				'prefix' => 'application',
			], function () {
				Route::get('{id}', 'ShowApplicationController@main');
				Route::put('{id}', 'UpdateApplicationController@main');
			});
		});
		Route::group([
			'namespace' => 'Renter',
			'prefix' => 'renter'
		], function () {
			Route::post('{id}/review', 'ReviewController@main');
			Route::post('rent-room', 'RentRoomController@main');
			Route::put('leave-room/{userId?}', 'LeaveRoomController@main');
			Route::post('wanted-roommate', 'WantedRoommateController@main');
			Route::put('wanted-roommate', 'UpdateWantedRoommateController@main');
			Route::delete('wanted-roommate', 'DestroyWantedRoommateController@main');
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
			'prefix' => 'house',
			'middleware' => ['admin-owner-role']
		], function () {
			Route::post('', 'StoreController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::post('{id}/images', 'UploadImagesController@main');
			Route::put('{id}/images', 'UpdateImagesController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Room',
			'prefix' => 'room',
			'middleware' => ['admin-owner-role']
		], function () {
			Route::get('{id}/renters', 'RenterController@main');
			Route::get('{id}/services', 'GetServicesController@main');
			Route::get('{id}/pay-methods', 'PayMethodController@main');
			Route::post('', 'StoreController@main');
			Route::post('{id}/images', 'UploadImagesController@main');
			Route::post('{id}/pay-methods', 'AddPayMethodController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::put('{id}/images', 'UpdateImagesController@main');
			Route::put('{id}/status', 'UpdateStatusController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Room',
			'prefix' => 'room'
		], function () {
			Route::get('{id}/pay-methods', 'PayMethodController@main');
		});
		Route::group([
			'namespace' => 'Service',
			'prefix' => 'service'
		], function () {
			Route::get('', 'IndexController@main');
			Route::get('list', 'ListController@main');
			Route::post('', 'StoreController@main')->middleware('admin-role');
			Route::put('{id}', 'UpdateController@main')->middleware('admin-role');
			Route::delete('{id}', 'DestroyController@main')->middleware('admin-role');
		});
		Route::group([
			'namespace' => 'Criteria',
			'prefix' => 'criteria',
			'middleware' => ['admin-role']
		], function () {
			Route::get('list', 'ListController@main');
			Route::post('', 'StoreController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Review',
			'prefix' => 'review',
			'middleware' => ['admin-role']
		], function () {
			Route::get('list/{type}', 'ListController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
		Route::group([
			'namespace' => 'Image',
			'prefix' => 'image'
		], function () {
			Route::post('', 'StoreController@main');
		});
		Route::group([
			'namespace' => 'Payment',
			'prefix' => 'payment'
		], function () {
			Route::group([
				'namespace' => 'Room',
				'prefix' => 'room'
			], function () {
				Route::get('list', 'ListController@main')->middleware('owner-renter-role');
				Route::get('{id}', 'ShowController@main')->middleware('owner-renter-role');
				Route::post('', 'StoreController@main')->middleware('admin-owner-role');
				Route::put('{id}', 'UpdateController@main')->middleware('admin-owner-role');
				Route::put('{id}/status', 'UpdateStatusController@main')->middleware('admin-owner-role');
				Route::delete('{id}', 'DestroyController@main')->middleware('admin-owner-role');
			});
		});
		Route::group([
			'namespace' => 'PayMethod',
			'prefix' => 'pay-method'
		], function () {
			Route::get('', 'IndexController@main');
			Route::get('list', 'ListController@main');
			Route::post('', 'StoreController@main');
			Route::put('{id}', 'UpdateController@main');
			Route::delete('{id}', 'DestroyController@main');
		});
	});
});
