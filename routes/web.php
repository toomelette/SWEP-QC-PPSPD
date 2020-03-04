<?php


/** Auth **/
Route::group(['as' => 'auth.'], function () {
	
	Route::get('/login', 'Auth\LoginController@showLoginForm')->name('showLogin');
	Route::post('/login', 'Auth\LoginController@login')->name('login');
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

});



/** Guest **/
Route::group(['as' => 'guest.'], function () {
	
	Route::get('/import', 'DocumentController@create')->name('document.create');
	Route::post('/import', 'DocumentController@store')->name('document.store');
	Route::get('/', 'DocumentController@index')->name('document.index');
	Route::get('/view_file/{slug}', 'DocumentController@viewFile')->name('document.view_file');
	Route::get('/download/{slug}', 'DocumentController@download')->name('document.download');
	Route::get('/{slug}/show', 'DocumentController@show')->name('document.show');
	Route::put('/{slug}', 'DocumentController@update')->name('document.update');
	Route::delete('/{slug}', 'DocumentController@destroy')->name('document.destroy');
	Route::delete('/destroy_hard/{slug}', 'DocumentController@destroyHard')->name('document.destroy_hard');
	Route::get('/archives', 'DocumentController@archives')->name('document.archives');
	Route::post('/restore/{slug}', 'DocumentController@restore')->name('document.restore');
	Route::post('/overwrite/replace/{slug}', 'DocumentController@overwriteReplace')->name('document.overwriteReplace');
	Route::post('/overwrite/skip/{slug}', 'DocumentController@overwriteSkip')->name('document.overwriteSkip');
	Route::post('/overwrite/keep_both/{slug}', 'DocumentController@overwriteKeepBoth')->name('document.overwriteKeepBoth');
	Route::get('/reports', 'DocumentController@reports')->name('document.reports');
	Route::get('/reports_print', 'DocumentController@reportsPrint')->name('document.reports_print');

});



/** Dashboard **/
Route::group(['prefix'=>'dashboard', 'as' => 'dashboard.', 'middleware' => ['check.user_status', 'check.user_route']], function () {

	/** HOME **/	
	Route::get('/home', 'HomeController@index')->name('home');


	/** USER **/   
	Route::post('/user/activate/{slug}', 'UserController@activate')->name('user.activate');
	Route::post('/user/deactivate/{slug}', 'UserController@deactivate')->name('user.deactivate');
	Route::post('/user/logout/{slug}', 'UserController@logout')->name('user.logout');
	Route::get('/user/{slug}/reset_password', 'UserController@resetPassword')->name('user.reset_password');
	Route::patch('/user/reset_password/{slug}', 'UserController@resetPasswordPost')->name('user.reset_password_post');
	Route::resource('user', 'UserController');


	/** PROFILE **/
	Route::get('/profile', 'ProfileController@details')->name('profile.details');
	Route::patch('/profile/update_account_username/{slug}', 'ProfileController@updateAccountUsername')->name('profile.update_account_username');
	Route::patch('/profile/update_account_password/{slug}', 'ProfileController@updateAccountPassword')->name('profile.update_account_password');
	Route::patch('/profile/update_account_color/{slug}', 'ProfileController@updateAccountColor')->name('profile.update_account_color');


	/** MENU **/
	Route::resource('menu', 'MenuController');

});




/** Testing **/
Route::get('/dashboard/test', function(){

	//return dd(Illuminate\Support\Str::random(16));

});

