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
	Route::any('permissions', 'Web\AdminController@permissions')
				->middleware('tracker:view permissions');
	Route::get('delete-permission/{uuid}', 'Web\AdminController@deletePermission')
				->middleware('tracker:Attempt to delete permission');
	Route::get('destroy-permission/{uuid}', 'Web\AdminController@destroyPermission')
				->middleware('tracker:Delete a permission', 'guardian');
	Route::get('permission/{uuid}', 'Web\AdminController@showPermission');
	Route::get('edit-permission/{uuid}', 'Web\AdminController@editPermission')
				->middleware('tracker:Attempt to edit a permission', 'guardian');
	Route::post('update-permission/{uuid}', 'Web\AdminController@updatePermission');
	
	Route::any('roles', 'Web\RoleController@roles');
	Route::get('create-role', 'Web\RoleController@createRole');
	Route::post('store-role', 'Web\RoleController@storeRole');
	Route::get('role/{uuid}', 'Web\RoleController@showRole');
	Route::get('edit-role/{uuid}', 'Web\RoleController@editRole');
	Route::post('update-role/{uuid}', 'Web\RoleController@updateRole');
	Route::get('delete-role/{uuid}', 'Web\RoleController@deleteRole');
	Route::get('destroy-role/{uuid}', 'Web\RoleController@destroyRole');
	Route::any('role-permission/{uuid}', 'Web\RoleController@rolePermission');
	Route::get('add-role-permission/{uuid}', 'Web\RoleController@addRolePermission');
	Route::post('store-role-permission/{uuid}', 'Web\RoleController@storeRolePermission');
});


Route::get('test', function(){
	return view('w3.create.permission');
});