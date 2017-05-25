<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('/signup', 'UsersController@create')->name('signup');//name this route as 'signup'
//to process this request, UsersController will call create action

Route::resource('users', 'UsersController');//'users' is the resource name, this line equals to the follwing codes
// get('/users', 'UsersController@index')->name('users.index');
// get('/users/{id}', 'UsersController@show')->name('users.show');
// get('/users/create', 'UsersController@create')->name('users.create');
// post('/users', 'UsersController@store')->name('users.store');
// get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
// patch('/users/{id}', 'UsersController@update')->name('users.update');
// delete('/users/{id}', 'UsersController@destroy')->name('users.destroy');

/*
  Going following routes will create and destroy session  for user's login
*/
Route::get('login', 'SessionController@create')->name('login');//display the login page
Route::post('login', 'SessionController@store')->name('login');//create session
//above two routes have same route name, laravel will assign it with correct action according to its get/post method
Route::delete('logout', 'SessionController@destroy')->name('logout');//destroy session

Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');//this route is user's activation link
