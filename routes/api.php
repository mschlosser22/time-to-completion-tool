<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('schools', 'SchoolsController@index');
Route::get('school/{school}', 'SchoolsController@show');
Route::get('programs/{school}', 'ProgramsController@index');
Route::get('program/{program}', 'ProgramsController@show');
Route::get('tracks/{program}', 'TracksController@index');
Route::get('track/{track}', 'TracksController@show');
Route::get('sessions/{school}', 'SessionsController@index');