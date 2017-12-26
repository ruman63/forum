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
Auth::routes();
Route::get('/register/confirm', 'Auth\RegisterConfirmController@index')->name('register.confirm');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('threads/create', "ThreadsController@create")->name('threads.create');
Route::get('threads/{channel}/{thread}', "ThreadsController@show")->name('threads.show');
Route::delete('threads/{channel}/{thread}', "ThreadsController@destroy")->name('threads.destroy');
Route::get('threads/{channel?}', "ThreadsController@index")->name('threads.index');

Route::post('threads', "ThreadsController@store")->name('threads.store')->middleware('confirm-user');

Route::post('threads/{channel}/{thread}/replies', "RepliesController@store")->name('replies.store');
Route::get('threads/{channel}/{thread}/replies', "RepliesController@index")->name('replies.index');

Route::post('threads/{channel}/{thread}/subscriptions', "ThreadSubscriptionsController@store")->name('thread.subscriptions.store');
Route::delete('threads/{channel}/{thread}/subscriptions', "ThreadSubscriptionsController@destroy")->name('thread.subscriptions.destroy');

Route::post('replies/{reply}/favorite', "FavoritesController@store")->name('replies.favorite');
Route::delete('replies/{reply}/favorite', "FavoritesController@destroy")->name('replies.unfavorite');
Route::patch('replies/{reply}', "RepliesController@update")->name('replies.update');
Route::delete('replies/{reply}', "RepliesController@destroy")->name('replies.destroy');

Route::get('profiles/{user}', "ProfilesController@show")->name('profiles');
Route::delete('profiles/{user}/notifications/{notification}', "NotificationsController@destroy")->name('notifications.destroy');
Route::get('profiles/{user}/notifications/', "NotificationsController@index")->name('notifications.index');

Route::get('api/users', "Api\UsersController@index");
Route::post('api/users/{user}/avatar', "Api\UsersAvatarController@store")->name('avatar');
