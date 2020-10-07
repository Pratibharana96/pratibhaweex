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
    return view('welcome');
});

Auth::routes(['verify'=>true]);

Route::get('/home', 'HomeController@index')->name('home'); 
//code for otp
Route::post('login', 'AuthController@login')->name('newlogin');
//login with verification with dummy otp  
Route::post('loginWithOtp', 'AuthController@loginWithOtp')->name('loginWithOtp');
Route::get('loginWithOtp', function () {
    return view('auth/OtpLogin');
})->name('loginWithOtp');
//login with verification with twilio otp 

Route::get('phone/verify', 'AuthController@twiliootpshow')->name('phoneverification.notice');
Route::post('phone/verify', 'AuthController@twilioverify')->name('phoneverification.verify');
Route::post('build-twiml/{code}', 'AuthController@buildTwiMl')->name('phoneverification.build'); 
//registration verification with email 
Route::get('/verify','AuthController@verifyUser')->name('verify.user');
//end
Route::post('sendOtp', 'AuthController@sendOtp');
Route::post('newregister', 'AuthController@register')->name('newregister');
//routes for social 
// Socialite Register Routes
// Route::get('login/{provider}', 'SocialController@redirect');
// Route::get('login/{provider}/callback','SocialController@Callback');
Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');

//routes for admin panel and role
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home/{id}', 'HomeController@show');
Route::get('admin/vendor', 'VendorController@show')->name('vendor'); 
Route::get('admin', 'AdminController@index');
Route::resource('posts', 'PostsController');
Route::resource('users', 'UserController');
Route::resource('roles', 'RolesController');


