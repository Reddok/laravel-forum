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

Route::resource('/threads', 'ThreadController')->except('show', 'store');

Route::view('/test', 'test');

Route::get('/threads/search', 'ThreadSearchController@index')->name('threads.search');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::get('/threads/{channel}', 'ThreadController@index')->name('threads.channel');
Route::get('/profiles/{user}', 'ProfileController@index')->name('profiles.index');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index')->name('replies.index');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index')->name('notifications.index');

Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.delete');
Route::delete('replies/{reply}', 'ReplyController@destroy')->name('replies.delete');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->name('replies.unfavorite');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->name('threadSubscriptions.unsubscribe');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->name('notifications.delete');

Route::patch('replies/{reply}', 'ReplyController@update')->name('replies.update');
Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('replies.favorite');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('replies.create');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->name('threadSubscriptions.create');


Route::get('/api/users', 'Api\UsersController@index')->name('api.users.index');
Route::get('/confirm', 'Auth\ConfirmationController@index')->name('confirmation.index');

Route::middleware(['auth'])->group(function() {
    Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('api.users.avatar.update');
});

Route::middleware(['auth', 'confirmation'])->group(function() {
    Route::post('/threads', 'ThreadController@store')->name('threads.store');
    Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
    Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');
});

Route::middleware(['admin'])->group(function() {
    Route::post('/lock-threads/{thread}', 'LockThreadsController@store')->name('lock-threads.store');
    Route::delete('/lock-threads/{thread}', 'LockThreadsController@destroy')->name('lock-threads.destroy');
});

Auth::routes();


