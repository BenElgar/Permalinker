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

Route::get('/', function () {
    return view('home');
});

Route::post('/store', [
    'as' => 'snapshot.store',
    'uses' => 'SnapshotController@store',
]);

Route::get('/404', [
    'as' => 'error.404',
    function() {
        return view('errors.404');
    }
]);

Route::get('/status/{snapshot_id}', [
    'as' => 'snapshot.status',
    'uses' => 'SnapshotController@status',
]);

Route::get('/{snapshot_id}', [
    'as' => 'snapshot.show',
    'uses' => 'SnapshotController@show',
]);

