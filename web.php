<?php

//---============ Login ==============----------
Route::get('login/{name}', 'Auth\LoginController@redirectTo');
Route::get('callback', 'Auth\LoginController@handleSocialCallback');

?>
