<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

$root_uri_segment = Lifeboy\Station\Config\StationConfig::app('root_uri_segment');
$path = 'Lifeboy\Station\Controllers\\';

// unprotected routes that require the web middleware not prefixed
Route::group(array('middleware' => ['web']), function() use ($path) {
    Route::get('/password/reset/{token}', $path.'StationUserController@password_reset');
});

// unprotected routes. user does not have to be logged in to access
Route::group(array('middleware' => ['web'], 'prefix' => $root_uri_segment), function() use ($path)
{
	$panel_for_user_create = Lifeboy\Station\Config\StationConfig::app('panel_for_user_create');
	Route::get('/login', $path.'StationSessionController@create');
	Route::get('/logout', $path.'StationSessionController@destroy');
	Route::resource('/sessions', $path.'StationSessionController', ['only' => ['store', 'create', 'destroy']]);
	Route::get('/register', $path.'StationPanelController@create_user');
    Route::post('/panel/'.$panel_for_user_create, $path.'StationPanelController@do_create_user');
	Route::post('/forgot', $path.'StationUserController@forgot');
	Route::get('/forgot', $path.'StationUserController@reminded');
	Route::get('/password/reset/{token}', $path.'StationUserController@password_reset');
	Route::post('/password/reset/{token}', $path.'StationUserController@do_reset_password');
});

// protected routes. user must be logged in.
Route::group(array('middleware' => ['web', 'station.session'], 'prefix' => $root_uri_segment), function() use ($path)
{
	Route::get('/', $path.'StationSessionController@bootstrap');
	Route::get('/home', $path.'StationSessionController@bootstrap');
    Route::get('/panel/{panel_name}', $path.'StationPanelController@index');
    Route::post('/panel/{panel_name}', $path.'StationPanelController@do_create');
    Route::post('/file/upload', $path.'StationFileController@upload');
    Route::put('/file/crop', $path.'StationFileController@crop');
    Route::get('/panel/{panel_name}/index', $path.'StationPanelController@index');
    Route::get('/panel/{panel_name}/search', $path.'StationPanelController@search');
    Route::get('/panel/{panel_name}/create', $path.'StationPanelController@create');
    Route::get('/panel/{panel_name}/create/for/{parent_panel}/{id}', $path.'StationPanelController@create_in_subpanel');
    Route::post('/panel/{panel_name}/create/for/{parent_panel}/{id}', $path.'StationPanelController@do_create_in_subpanel');
    Route::get('/panel/{panel_name}/read/{id}', $path.'StationPanelController@update');
    Route::get('/panel/{panel_name}/update/{id}', $path.'StationPanelController@update');
    Route::put('/panel/{panel_name}/update/{id}', $path.'StationPanelController@do_update');
    Route::put('/panel/{panel_name}/process_url/{element_name}', $path.'StationFileController@process_url');
    Route::put('/panel/{panel_name}/update_element/{element_name}/{id}', $path.'StationPanelController@do_update_element');
    Route::put('/panel/{panel_name}/update_element_for_ids/{element_name}/{ids}', $path.'StationPanelController@do_update_element_for_ids');
    Route::put('/panel/{panel_name}/{parent_panel}/{parent_id}/update_element/{element_name}/{id}', $path.'StationPanelController@do_update_element_in_subpanel');
    Route::get('/panel/{panel_name}/update/{id}/for/{parent_panel}/{parent_id}', $path.'StationPanelController@update_in_subpanel');
    Route::put('/panel/{panel_name}/update/{id}/for/{parent_panel}/{parent_id}', $path.'StationPanelController@do_update_in_subpanel');
    Route::put('/panel/{panel_name}/reorder/', $path.'StationPanelController@do_reorder');
    Route::put('/panel/{panel_name}/reorder_nested/', $path.'StationPanelController@do_reorder_nested');
    Route::put('/panel/{panel_name}/{parent_panel}/{parent_id}/reorder/', $path.'StationPanelController@do_reorder_in_subpanel');
    Route::delete('/panel/{panel_name}/delete/{id}', $path.'StationPanelController@do_delete');
    Route::delete('/panel/{panel_name}/{parent_panel}/{parent_id}/delete/{id}', $path.'StationPanelController@do_delete_in_subpanel');

});
