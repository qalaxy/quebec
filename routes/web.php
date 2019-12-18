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
	//Route::get('create-error', 'Web\FuncErrorController@createError')->name('error');
	
	/*
	 * ======================================================================================================
	 * Permission routes
	 * 
	 */
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
	
	/*
	 * =====================================================================================================
	 * Role routes
	 *
	 */
	
	Route::any('roles', 'Web\RoleController@roles');
	Route::get('create-role', 'Web\RoleController@createRole');
	Route::post('store-role', 'Web\RoleController@storeRole');
	Route::get('role/{uuid}', 'Web\RoleController@showRole');
	Route::get('edit-role/{uuid}', 'Web\RoleController@editRole');
	Route::post('update-role/{uuid}', 'Web\RoleController@updateRole');
	Route::get('delete-role/{uuid}', 'Web\RoleController@deleteRole');
	Route::get('destroy-role/{uuid}', 'Web\RoleController@destroyRole');
	Route::any('role-permissions/{uuid}', 'Web\RoleController@rolePermissions');
	Route::get('add-role-permission/{uuid}', 'Web\RoleController@addRolePermission');
	Route::post('store-role-permission/{uuid}', 'Web\RoleController@storeRolePermission');
	Route::get('delete-role-permission/{role_uuid}/{perm_uuid}', 'Web\RoleController@deleteRolePermission');
	Route::get('destroy-role-permission/{role_uuid}/{perm_uuid}', 'Web\RoleController@destroyRolePermission');
	Route::get('get-role-stations', 'Web\RoleController@getRoleStations');
	Route::get('get-role-user-stations', 'Web\RoleController@getRoleUserStations');
	
	/*
	 * =========================================================================================================
	 * User account routes
	 * 
	 */
	Route::any('accounts', 'Web\AccountController@accounts');
	Route::get('create-account', 'Web\AccountController@createAccount');
	Route::post('store-account', 'Web\AccountController@storeAccount');
	Route::get('account/{uuid}', 'Web\AccountController@showAccount');
	Route::get('edit-account/{uuid}', 'Web\AccountController@editAccount');
	Route::post('update-account/{uuid}', 'Web\AccountController@updateAccount');
	Route::get('delete-account/{uuid}', 'Web\AccountController@deleteAccount');
	Route::get('destroy-account/{uuid}', 'Web\AccountController@destroyAccount');
	Route::get('first-login/{uuid}', 'Web\AccountController@accountFirstLogin');
	Route::post('first-auth/{uuid}', 'Web\AccountController@accountFirstAuth');
	
	Route::get('emails/{uuid}', 'Web\AccountController@addEmail');
	Route::get('add-email/{uuid}', 'Web\AccountController@addEmail');
	Route::post('store-email/{uuid}', 'Web\AccountController@storeEmail');
	Route::get('edit-email/{account_uuid}/{email_uuid}', 'Web\AccountController@editEmail');
	Route::post('update-email/{account_uuid}/{email_uuid}', 'Web\AccountController@updateEmail');
	Route::get('delete-email/{account_uuid}/{email_uuid}', 'Web\AccountController@deleteEmail');
	Route::get('destroy-email/{account_uuid}/{email_uuid}', 'Web\AccountController@destroyEmail');
	
	Route::get('add-station/{account_uuid}', 'Web\AccountController@addAccountStation');
	Route::post('store-account-station/{account_uuid}', 'Web\AccountController@storeAccountStation');
	Route::get('edit-account-station/{account_uuid}/{stn_uuid}', 'Web\AccountController@editAccountStation');
	Route::post('update-account-station/{account_uuid}/{stn_uuid}', 'Web\AccountController@updateAccountStation');
	Route::get('delete-account-station/{account_uuid}/{stn_uuid}', 'Web\AccountController@deleteAccountStation');
	Route::get('destroy-account-station/{account_uuid}/{stn_uuid}', 'Web\AccountController@destroyAccountStation');
	Route::get('account-station/{uuid}', 'Web\AccountController@getAccountStation');
	Route::any('account-stations/{uuid}', 'Web\AccountController@accountStations');
	
	
	Route::get('account-supervisory/{uuid}', 'Web\AccountController@accountSupervisory');
	Route::get('add-account-supervisory/{uuid}', 'Web\AccountController@addAccountSupervisory');
	Route::post('store-account-supervisory/{uuid}', 'Web\AccountController@storeAccountSupervisory');
	Route::get('edit-account-supervisory/{account_uuid}/{sup_uuid}', 'Web\AccountController@editAccountSupervisory');
	Route::post('update-account-supervisory/{account_uuid}/{sup_uuid}', 'Web\AccountController@updateAccountSupervisory');
	Route::get('delete-account-supervisory/{account_uuid}/{sup_uuid}', 'Web\AccountController@deleteAccountSupervisory');
	Route::get('destroy-account-supervisory/{account_uuid}/{sup_uuid}', 'Web\AccountController@destroyAccountSupervisory');
	Route::get('account-supervisories/{account_uuid}', 'Web\AccountController@accountSupervisories');
	
	Route::get('add-role/{account_uuid}', 'Web\AccountController@addRole');
	Route::post('store-account-role/{account_uuid}', 'Web\AccountController@storeAccountRole');
	Route::get('delete-account-role/{account_uuid}/{role_uuid}', 'Web\AccountController@deleteAccountRole');
	Route::get('destroy-account-role/{account_uuid}/{role_uuid}', 'Web\AccountController@destroyAccountRole');
	Route::get('account-role/{uuid}', 'Web\AccountController@accountRole');
	
	/*
	 * ======================================================================================================================
	 * Error routes
	 *
	 * It routes the request to error controller
	 * User should be authenticated first before accessing the request
	 *
	 */	
	Route::any('errors-pdf', 'Web\ErrorController@errorsPdf');
	Route::any('errors', 'Web\ErrorController@errors');
	Route::any('error-notifications', 'Web\ErrorController@errorNotifications');
	//Route::any('count-error-notifications', 'Web\ErrorController@countErrorNotifications');
	Route::get('create-error', 'Web\ErrorController@createError');
	Route::get('get-station-functions/{uuid}', 'Web\ErrorController@getStationFunctions');
	Route::post('store-error', 'Web\ErrorController@storeError');
	Route::get('error/{uuid}', 'Web\ErrorController@showError');
	Route::get('pdf-error/{uuid}', 'Web\ErrorController@pdfError');
	Route::get('error-pdf/{uuid}', 'Web\ErrorController@errorPdf');
	Route::get('edit-error/{uuid}', 'Web\ErrorController@editError');
	Route::post('update-error/{uuid}', 'Web\ErrorController@updateError');
	Route::get('delete-error/{uuid}', 'Web\ErrorController@deleteError');
	Route::get('destroy-error/{uuid}', 'Web\ErrorController@destroyError');
	
	Route::get('add-error-affected-product/{uuid}', 'Web\ErrorController@addErrorProduct');
	Route::post('store-error-affected-product/{uuid}', 'Web\ErrorController@storeErrorProduct');
	
	Route::get('error-corrective-action/{uuid}', 'Web\ErrorController@addCorrectiveAction');
	Route::get('get-account-station/{uuid}', 'Web\ErrorController@getAccountStation');
	Route::post('store-error-corrective-action/{uuid}', 'Web\ErrorController@storeCorrectiveAction');
	
	/*
	 * ========================================================================================================================
	 * Station routes
	 * 
	 */
	Route::any('stations', 'Web\StationController@stations');
	Route::get('station/{uuid}', 'Web\StationController@station');
	Route::get('add-station-function/{uuid}', 'Web\StationController@addStationFunction');
	Route::post('store-station-function/{uuid}', 'Web\StationController@storeStationFunction');
	Route::get('delete-station-function/{stn_uuid}/{func_uuid}', 'Web\StationController@deleteStationFunction');
	Route::get('destroy-station-function/{stn_uuid}/{func_uuid}', 'Web\StationController@destroyStationFunction');
	Route::get('add-station-recipient/{uuid}', 'Web\StationController@addStationRecipient');
	Route::post('store-station-recipient/{uuid}', 'Web\StationController@storeStationRecipient');
	Route::get('delete-station-recipient/{stn_uuid}/{user_uuid}', 'Web\StationController@deleteStationRecipient');
	Route::get('destroy-station-recipient/{stn_uuid}/{user_uuid}', 'Web\StationController@destroyStationRecipient');

});


Route::get('test', function(){
	return view('w3.create.permission');
});