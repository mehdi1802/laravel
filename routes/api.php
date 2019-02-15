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

Route::post('/login', 'AuthController@authenticate');

/**
 * We used middleware to make sure that these api calls only accessible to 
 * authenticated users
 */
Route::middleware('jwt.auth')->group(function () {
    Route::post('/users/update/{user_id}', 'UserController@updateUser');
    Route::post('/users/create/', 'UserController@addUser');
    Route::get('/users', 'UserController@getAll');
    
});