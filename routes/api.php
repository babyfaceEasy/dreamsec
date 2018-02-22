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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kunle', 'ClientController@registerClient');*/
Route::get('/test-send-mail', 'ClientController@testMail');
Route::get('/token', 'ClientController@resetPasswordLink');
Route::get('/test-sms', 'DataController@testSMS');


Route::group(array( 'prefix' => 'users' ),function(){
    Route::post('register', 'ClientController@registerClient');
    Route::post('activate', 'ClientController@activateClient');
    Route::post('login', 'ClientController@loginClient');
    Route::post('getdetails', 'ClientController@getClientDetails');
    Route::post('updatedetails', 'ClientController@updateClientDetails');
    Route::post('resend-activation', 'ClientController@resendActivationCode');
    Route::post('sendclientresetlink', 'ClientController@resetPasswordLink');
    //Route::post('reset-client-pwd', 'ClientController@resetClientPassword')->name('client.reset.pwd');
    //Route::get('clientresetlink/{token}', 'ClientController@getResetPage')->name('client.reset.link');
});//end of user->prefix

Route::prefix('data')->group(function(){
    Route::post('post', 'DataController@create');
    //Route::post('')
});

Route::prefix('report')->group(function(){
   Route::post('getAll', 'ClientController@getClientICEReports') ;
});
