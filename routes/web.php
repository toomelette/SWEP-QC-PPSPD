<?php

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




/** Testing **/
Route::get('/dashboard/test', function(){

	//return dd(Illuminate\Support\Str::random(16));

});

