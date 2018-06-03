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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});





/*
Route::post('/auth/register', [
    'as' => 'auth.register',
    'uses' => 'Api/AuthController@register'
  ]);
  Route::post('/auth/login', [
    'as' => 'auth.login',
    'uses' => 'Api/AuthController@login'
  ]);
*/




Route::group([
  //'prefix' => 'api/v1',
  'namespace' => 'Api'
], function () {
        Route::post('/auth/register', [
            'as' => 'auth.register',
            'uses' => 'AuthController@register'
        ]);
        Route::post('/auth/login', [
            'as' => 'auth.login',
            'uses' => 'AuthController@login'
        ]);

        Route::post('/user/update_avatar', [
          'as' => 'user.update_avatar',
          'uses' => 'UserController@update_avatar'
      ]);
                
        //Route::post('/get_user_details', 'HomeController@get_user_details');
        
        Route::group(['middleware' => 'jwt-auth'], function () {
            Route::get('/get_user_details', 'HomeController@get_user_details');
           // Route::get('/user/update', 'UserController@update');
        });


        Route::get('/get_user_details',[
                'as' => 'user.details',
                'uses' => 'HomeController@get_user_details'
            ]);


        Route::get('user/activation/{token}', 'AuthController@activateUser')->name('user.activate');
});

      //Route::get('user/activation/{token}', 'Auth\LoginController@activateUser')->name('user.activate');








/*
Route::group([
  'prefix' => 'api/v1',
  'namespace' => 'Api'
], function () {
  Route::post('/auth/register', [
    'as' => 'auth.register',
    'uses' => 'AuthController@register'
  ]);
  Route::post('/auth/login', [
    'as' => 'auth.login',
    'uses' => 'AuthController@login'
  ]);
});
*/