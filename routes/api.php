<?php

// Submenu
Route::get('/submenu/select_submenu_byMenuId/{menu_id}', 'Api\ApiSubmenuController@selectSubmenuByMenuId')
		->name('selectSubmenuByMenuId');

// Folder
Route::get('/folder/{slug}/edit', 'Api\ApiFolderController@edit')->name('api.folder_edit');


