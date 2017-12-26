<?php


Route::group(
	[
		'namespace' => 'Setting'
	], function(){

		Route::resource('setting', 'SettingController');

});	