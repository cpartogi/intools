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

Route::prefix('ajax')->group(function () {
    Route::get('/rate-city/list', 'RateCityController@GetList')->name('list_lrc')->middleware('auth');
    Route::post('/lrc/switch', 'RateCityController@switchLRC')->name('switch_lrc')->middleware('auth');

    //AREA
    Route::get('/provinces/{countryID}', 'AdminController@listProvinces')->name('getProvinces')->middleware('auth');
    Route::get('/cities/{provinceID}', 'AdminController@listCities')->name('getCities')->middleware('auth');
    Route::get('/suburbs/{cityID}', 'AdminController@listSuburbs')->name('getSuburbs')->middleware('auth');
    Route::get('/areas/{suburbID}', 'AdminController@listAreas')->name('getAreas')->middleware('auth');
    Route::get('/rates/{logisticID}', 'AdminController@getRates')->name('getRates')->middleware('auth');
    Route::get('/areas', 'AdminController@getAreas')->name('list_area')->middleware('auth');
    Route::get('/logistics', 'AdminController@getLogistics')->name('list_logistic')->middleware('auth');
    Route::get('/country', 'AdminController@getCountry')->name('list_country')->middleware('auth');

    // stop ref
    Route::get('/stopref/update', 'StopRefController@updateStopRef')->name('update_stopref')->middleware('auth');
    Route::get('/stopref/link', 'StopRefController@GetList')->name('list_stopref')->middleware('auth');

    // shipment pricing
    Route::get('/shipment-pricing/list', 'ShipmentPricingController@search')->name('search_shipment_pricing')->middleware('auth');

    // user management
    Route::get('/user/list', 'UserController@getList')->name('getUser')->middleware('auth');
    Route::post('/user/switch', 'UserController@switchActive')->name('switch_active')->middleware('auth');
    Route::post('/user/update-role', 'UserController@updateRole')->name('update_user_role')->middleware('auth');

    // role management
    Route::get('/user-role/list', 'RoleController@getList')->name('getRole')->middleware('auth');
    Route::post('/user-role/update-perm', 'RoleController@updatePerm')->name('update_role_perm')->middleware('auth');

    // rate service management
    Route::get('/rate-service/list', 'RateServiceController@getList')->name('getRateService')->middleware('auth');

    // suburb management
    Route::get('/suburb-management/list', 'SuburbController@getList')->name('suburb_list')->middleware('auth');

    // commission calculation date
    Route::get('/commission/calculation_schedule', 'CommissionCalculationDateController@getList')->name('get_commission_calculation_date')->middleware('auth');

    // bank list
    Route::get('/banks', 'WithdrawDepositController@getBanks')->name('bank_list')->middleware('auth');

    // Depoosit manipulation
    Route::post('/deposit-manipulation', 'DepositManipulationController@store')->name('depositManipulation.store')->middleware('auth');

    // Commission Calculation Date
    Route::post('/commission-calculation-date', 'CommissionCalculationDateController@store')->name('commissionCalculationDate.store')->middleware('auth');
    Route::post('/commission-calculation-date/{id}', 'CommissionCalculationDateController@update')->name('commissionCalculationDate.update')->middleware('auth');

    // Withdraw Deposit
    Route::post('/withdraw-deposit', 'WithdrawDepositController@store')->name('withdrawDeposit.store')->middleware('auth');

    // Top up Deposit
    Route::post('/top-up-deposit', 'TopUpDepositController@store')->name('topUpDeposit.store')->middleware('auth');
});

// shipment pricing
Route::get('/shipment-pricing', 'ShipmentPricingController@list')->middleware('auth');

// rate service management
Route::get('/rate-service', 'RateServiceController@list')->name('rateService')->middleware('auth');
Route::get('/rate-service/baru', 'RateServiceController@create')->name('rateService.create')->middleware('auth');
Route::post('/rate-service', 'RateServiceController@store')->name('rateService.store')->middleware('auth');

// Suburb Management
Route::get('/suburb-management', 'SuburbController@list')->name('suburb')->middleware('auth');
Route::get('/suburb-management/baru', 'SuburbController@create')->name('suburb.create')->middleware('auth');
Route::post('/suburb-management', 'SuburbController@store')->name('suburb.store')->middleware('auth');
Route::get('/suburb-management/{id}', 'SuburbController@edit')->name('suburb.edit')->middleware('auth');
Route::post('/suburb-management/{id}', 'SuburbController@update')->name('suburb.update')->middleware('auth');

Route::get('/healthz', 'AdminController@healthz');
Route::get('/', 'AdminController@dashboard')->middleware('auth');
Route::prefix('auth')->group(function () {
    Route::get('/logout', 'UserController@logout')->name('logout')->middleware('auth');
});

Route::get('/user-management', 'UserController@list')->middleware('auth');
Route::get('/stopref', 'AdminController@liststopref')->middleware('auth');
Route::get('/logistic-rate-city', 'RateCityController@listLogisticRateCity')->middleware('auth')->name('logitic-rate-city');

// user role management
Route::get('/user-role-management', 'RoleController@list')->name('role')->middleware('auth');
Route::get('/user-role-management/baru', 'RoleController@create')->name('role.create')->middleware('auth');
Route::post('/user-role-management', 'RoleController@store')->name('role.store')->middleware('auth');
Route::get('/user-role-management/{id}', 'RoleController@edit')->name('role.edit')->middleware('auth');
Route::post('/user-role-management/{id}', 'RoleController@update')->name('role.update')->middleware('auth');
Route::delete('/user-role-management/{id}', 'RoleController@delete')->name('role.delete')->middleware('auth');

// Midtrans callback process
Route::get('/midtrans-callback-process', 'MidtransCallbackProcessController@list')->name('midtransCallbackProcess')->middleware('auth');
Route::post('/midtrans-callback-process', 'MidtransCallbackProcessController@hitApi')->name('midtransCallbackProcess.hitApi')->middleware('auth');

// Commission calculation date
Route::get('/commission-calculation-date', 'CommissionCalculationDateController@list')->name('commissionCalculationDate')->middleware('auth');
Route::get('/commission-calculation-date/baru', 'CommissionCalculationDateController@create')->name('commissionCalculationDate.create')->middleware('auth');
Route::get('/commission-calculation-date/{id}', 'CommissionCalculationDateController@edit')->name('commissionCalculationDate.edit')->middleware('auth');

// Deposit Manipulation
Route::get('/deposit-manipulation', 'DepositManipulationController@create')->name('depositManipulation.create')->middleware('auth');

// Withdaw Deposit
Route::get('/withdraw-deposit', 'WithdrawDepositController@create')->name('withdrawDeposit.create')->middleware('auth');

// Top Up Deposit Manual
Route::get('/top-up-deposit', 'TopUpDepositController@create')->name('topUpDeposit.create')->middleware('auth');
