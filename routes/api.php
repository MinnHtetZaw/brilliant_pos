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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::post('Login', 'Api\LoginController@loginProcess');

Route::group(['middleware' => ['auth:api','CustomerPermissionAPI']], function () {

	Route::post('Logout', 'Api\LoginController@logoutProcess');
	
	Route::post('updatePassword', 'Api\LoginController@updatePassword');

	Route::post('editProfile', 'Api\CustomerController@editProfile');

	Route::post('getItemListbyCategory', 'Api\CustomerController@getItemListbyCategory');

	Route::post('getCountingUnit', 'Api\CustomerController@getCountingUnit');

	Route::post('storeOrder', 'Api\CustomerController@storeOrder');

	Route::post('getOrderList', 'Api\CustomerController@getOrderList');
	
	Route::post('getOrderDetails', 'Api\CustomerController@getOrderDetails');

	Route::post('changeOrder', 'Api\CustomerController@changeOrder');

	Route::post('acceptOrder', 'Api\CustomerController@acceptOrder');

	Route::post('delivery/sendlocation', 'Api\DeliveryController@deliverySendlocation');

	Route::post('delivery/getlocation', 'Api\DeliveryController@deliveryGetlocation');
});


