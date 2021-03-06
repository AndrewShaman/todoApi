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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::apiResource('projects', 'ProjectController');
    
    Route::get('projects/{project}/tasks', 'TaskController@index');
    Route::post('projects/{project}/tasks', 'TaskController@store');
    Route::get('projects/{project}/tasks/{task}', 'TaskController@show');
    Route::patch('projects/{project}/tasks/{task}', 'TaskController@update');
    Route::delete('projects/{project}/tasks/{task}', 'TaskController@destroy');
});

