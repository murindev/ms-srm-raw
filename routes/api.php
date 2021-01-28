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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('registration', 'AuthController@registration');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    // Route::post('user', 'UserController@getAuthenticatedUser');
    Route::post('closed', 'DataController@closed');
    Route::any('master', '\App\Http\Dart\MasterDart@index');
});
Route::group([
    'prefix' => 'personal'
], function (){
    Route::post('starts', 'Personal\Test@async')->middleware('auth');
});


