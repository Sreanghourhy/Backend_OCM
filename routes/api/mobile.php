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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/** SIGNING SECTION */
Route::group([
    'prefix' => 'authentication'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');

});

/** USER/ACCOUNT SECTION */
Route::group([
    'prefix' => 'users' ,
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('','UserController@index');
        Route::post('create','UserController@store');
        Route::put('update','UserController@update');
        Route::get('{id}/read','UserController@read');
        Route::delete('{id}/delete','UserController@destroy');
        Route::put('activate','UserController@active');
        Route::put('password/change','UserController@passwordChange');
});

/** RESET PASSWORD */
Route::group([
    'prefix' => 'user' ,
    'namespace' => 'Api' ,
    'middleware' => 'api'
    ], function() {
        Route::put('forgot_password','UserController@forgotPassword');
        Route::put('check_confirm_code','UserController@checkConfirmationCode');
        Route::put('passwordreset','UserController@passwordReset');
});

/** SEARCH SECTION */
Route::group([
    'prefix' => 'regulators' ,
    'namespace' => 'Api' ,
    'middleware' => 'api'
    ], function() {
        Route::get('','SearchController@index');
        Route::get('pdf','SearchController@pdf');
        Route::get('get/regulator/years','SearchController@getYears');
        Route::group([
            'prefix' => 'types' ,
            ], function() {
                Route::get('compact', "TypeController@compactList");
        });
        Route::get('types/compact', "TypeController@compactList");
        Route::get('read','RegulatorController@read');
        Route::post('','RegulatorController@create');
        Route::put('','RegulatorController@update');
        Route::delete('','RegulatorController@destroy');

});
/** FOLDER SECTION */
Route::group([
    'prefix' => 'folders' ,
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('list','FolderController@index');
        Route::get('{folderId}/regulator/{regulatorId}/add','FolderController@addRegulatorToFolder');
        Route::get('{folderId}/regulator/{regulatorId}/remove','FolderController@removeRegulatorToFolder');
        Route::put('checkregulator','FolderController@checkRegulator');
        Route::put('delete','FolderController@delete');
        Route::post('store','FolderController@store');
        Route::get('user','FolderController@user');
});
/** SECTION OF DOCUMENT TYPE */
Route::group([
    'prefix' => 'types' ,
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('','TypeController@index');
});

/** SECTION OF MINISTRY */
Route::group([
    'prefix' => 'ministries' ,
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('','MinistryController@index');
});

/** PROFILE SECTION */
Route::group([
    'prefix' => 'profile',
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
], function() {
    Route::get('/getAuthUser',
                'ProfileController@getAuthUser');
    Route::put('/updateAuthUser',
                'ProfileController@updateAuthUser');
    Route::put('/updateAuthUserPassword',
                'ProfileController@updateAuthUserPassword');
    Route::post('/picture/upload','ProfileController@upload');
});
