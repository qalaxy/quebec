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

Route::get('/', function () {
    return view('w3.index.home');
})->middleware('auth');

Auth::routes();

Route::get('/home', function(){
	return view('w3.index.home');
})->middleware('auth');


Route::get('uuid', function(){
	return Uuid::generate();
})->middleware('auth');

Route::group(['middleware'=>['auth', 'web']], function(){
	Route::get('create-error', 'Web\FuncErrorController@createError')->name('error');
	Route::get('create-permission', 'Web\AdminController@createPermission');
	Route::post('store-permission', 'Web\AdminController@storePermission');
	Route::get('permissions', 'Web\AdminController@permissions');
	Route::get('delete-permission/{uuid}', 'Web\AdminController@deletePermission');
	Route::get('destroy-permission/{uuid}', 'Web\AdminController@destroyPermission')->middleware('guardian');
	Route::get('permission/{uuid}', 'Web\AdminController@showPermission');
	Route::get('edit-permission/{uuid}', 'Web\AdminController@editPermission')->middleware('guardian');
	Route::post('update-permission/{uuid}', 'Web\AdminController@updatePermission');
});


Route::get('test', function(){
	return view('w3.create.permission');
});