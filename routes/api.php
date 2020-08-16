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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'store'], function () {
    Route::get('get', 'StoreController@index');
    Route::get('get/{id}', 'StoreController@show');
    Route::post('create', 'StoreController@store');
    Route::post('update/{id}', 'StoreController@update');
    Route::post('delete/{id}', 'StoreController@delete');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('get', 'ProductController@index');
    Route::get('get/{id}', 'ProductController@show');
    Route::post('create', 'ProductController@store');
    Route::post('update/{id}', 'ProductController@update');
    Route::post('inactivate/{id}', 'ProductController@inactivate');
    Route::post('activate/{id}', 'ProductController@activate');
    Route::post('delete/{id}', 'ProductController@delete');
});