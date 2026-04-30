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

use App\Http\Controllers\Api\Card\AuthController;
use App\Http\Controllers\Api\Card\UserController;
use App\Http\Controllers\Api\Card\PeopleController;
use App\Http\Controllers\Api\Card\ProfileController;

Route::group([
    'prefix' => 'card' ,
    'api'
  ],function(){

    /** SIGNING SECTION */
    Route::group([
        'prefix' => 'authentication'
    ], function () {
        Route::post('login', [AuthController::class,'login']);
        Route::group([
        'middleware' => 'auth:api'
        ], function() {
            Route::post('logout', [AuthController::class,'logout']);
            Route::get('user', [AuthController::class,'user']);
        });
    });

    /** USER/ACCOUNT SECTION */
    Route::group([
        'prefix' => 'users' ,
        'namespace' => 'Api' ,
        'middleware' => 'auth:api'
        ], function() {
            Route::get('',[UserController::class,'index']);
            // Route::post('create',[UserController::class,'store']);
            // Route::put('update',[UserController::class,'update']);
            Route::put('authenticated',[ProfileController::class,'updateAuthUser']);
            Route::get('{id}/read',[UserController::class,'read']);
            // Route::delete('{id}/delete',[UserController::class,'destroy']);
            // Route::put('activate',[UserController::class,'active']);
            // Route::put('password/change',[UserController::class,'passwordChange']);

            // Use to check the account does exist or not base on the phone or email or officer_identification_number
            Route::get('checkidentification/{term}/{type}',[UserController::class,'checkIdentification']);
            
            /**
             * Check the unique user information
             */
            Route::get('username/exist',[UserController::class,'checkUsername']);
            Route::get('phone/exist',[UserController::class,'checkPhone']);
            Route::get('email/exist',[UserController::class,'checkEmail']);
            // Route::post('upload',[UserController::class,'upload']);
            // Route::post('profile/photo/change',[UserController::class,'updateUserProfile']);
    });
    Route::group([
        'prefix' => 'users' ,
        'namespace' => 'Api' ,
        'middleware' => 'api'
        ], function() {
        Route::get('checkidentification/{term}/{type}',[UserController::class,'checkIdentification']);
    });

    /** PEOPLE / USER INFORMATION SECTION */
    Route::group([
        'prefix' => 'people' ,
        'namespace' => 'Api' ,
        'middleware' => 'auth:api'
        ], function() {
            Route::get('',[PeopleController::class,'index']);
            // Route::post('create',[PeopleController::class,'store']);
            // Route::put('update',[PeopleController::class,'update']);
            // Route::get('{id}/read',[PeopleController::class,'read']);
            // Route::delete('{id}/delete',[PeopleController::class,'destroy']);
            // Route::put('update_organization_code',[PeopleController::class,'updateOrganizationCode']);
            
    });

    /** PEOPLE / USER INFORMATION SECTION */
    Route::group([
        'prefix' => 'people' ,
        'namespace' => 'Api' ,
        'middleware' => 'api'
        ], function() {
            Route::get('{id}/read',[PeopleController::class,'read']);
    });

});
