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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('orders', 'API\OrderController@getOrders');
Route::get('orders/average', 'API\OrderController@getAverage');
Route::get('orders/customers/average', 'API\OrderController@getAveragePerUser');
Route::get('customers', 'API\CustomerController@getCustomers');
Route::get('line_items/average', 'API\OrderController@getLineItems');
