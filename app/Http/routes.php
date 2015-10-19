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

Route::get('/{snapshot_id}', function($snapshot_id) {
    return redirect()->route('snapshot.show', [$snapshot_id, 'index.html']);
});


Route::get('/{snapshot_id}/{file_name}', [
    'as' => 'snapshot.show',
    'uses' => 'SnapshotController@show',
]);
