<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Api\AuthenticationCenter\AuthController;
use App\Http\Controllers\Api\AuthenticationCenter\UserController;
use App\Http\Controllers\Api\AuthenticationCenter\PeopleController;
use App\Http\Controllers\Api\AuthenticationCenter\RoleController;
use App\Http\Controllers\Api\AuthenticationCenter\ProfileController;
use App\Http\Controllers\Api\AuthenticationCenter\TelegramPasswordController;

Route::group([
    'prefix' => 'authcenter' ,
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
            Route::get('confirm', [AuthController::class,'confirmAuthentication']);

        });
    });
    //telegram passwordless login
    Route::prefix('password/forgot/telegram')->group(function () {
        Route::put('/send', [TelegramPasswordController::class, 'sendOtp']);
        Route::put('/verify', [TelegramPasswordController::class, 'verifyOtp']);
        Route::put('/reset', [TelegramPasswordController::class, 'resetPassword']);
    });

    /** USER/ACCOUNT SECTION */
    Route::group([
        'prefix' => 'users' ,
        'namespace' => 'Api' ,
        'middleware' => 'auth:api'
        ], function() {
            Route::get('',[UserController::class,'index']);
            Route::post('create',[UserController::class,'store']);
            Route::put('update',[UserController::class,'update']);
            Route::put('authenticated',[ProfileController::class,'updateAuthUser']);
            Route::get('{id}/read',[UserController::class,'read']);
            Route::delete('{id}/delete',[UserController::class,'destroy']);
            Route::put('activate',[UserController::class,'active']);
            Route::put('password/change',[UserController::class,'passwordChange']);

            // Use to check the account does exist or not base on the phone or email or officer_identification_number
            Route::get('checkidentification/{term}/{type}',[UserController::class,'checkIdentification']);

            /**
             * Check the unique user information
             */
            Route::get('username/exist',[UserController::class,'checkUsername']);
            Route::get('phone/exist',[UserController::class,'checkPhone']);
            Route::get('email/exist',[UserController::class,'checkEmail']);
            Route::post('upload',[UserController::class,'upload']);
            Route::post('profile/photo/change',[UserController::class,'updateUserProfile']);
            Route::put('officer/update',[UserController::class,'officerUpdate']);
            Route::put('people/update',[UserController::class,'peopleUpdate']);
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
            Route::post('create',[PeopleController::class,'store']);
            Route::put('update',[PeopleController::class,'update']);
            // Route::get('{id}/read',[PeopleController::class,'read']);
            Route::delete('{id}/delete',[PeopleController::class,'destroy']);
            Route::put('update_organization_code',[PeopleController::class,'updateOrganizationCode']);

    });

    /** PEOPLE / USER INFORMATION SECTION */
    Route::group([
        'prefix' => 'people' ,
        'namespace' => 'Api' ,
        'middleware' => 'api'
        ], function() {
            Route::get('{id}/read',[PeopleController::class,'read']);
    });

    /** ROLE SECTION */
    Route::group([
        'prefix' => 'roles' ,
        'namespace' => 'Api' ,
        'middleware' => 'auth:api'
        ], function() {
            Route::get('',[RoleController::class,'index']);
            Route::post('create',[RoleController::class,'store']);
            Route::put('update',[RoleController::class,'update']);
            Route::get('{id}/read',[RoleController::class,'read']);
            Route::delete('{id}/delete',[RoleController::class,'destroy']);
    });

    /** USER/ACCOUNT SECTION */
    Route::group([
        'prefix' => 'users' ,
        'namespace' => 'Api' ,
        'middleware' => 'api'
        ], function() {
            Route::get('',[UserController::class,'index']);
            // Route::post('create',[UserController::class,'store']);
            // Route::put('update',[UserController::class,'update']);
            // Route::put('authenticated',[ProfileController::class,'updateAuthUser']);
            Route::get('{id}/read',[UserController::class,'read']);
            // Route::delete('{id}/delete',[UserController::class,'destroy']);
            // Route::put('activate',[UserController::class,'active']);
            // Route::put('password/change',[UserController::class,'passwordChange']);

            // Use to check the account does exist or not base on the phone or email or officer_identification_number
            // Route::get('checkidentification/{term}/{type}',[UserController::class,'checkIdentification']);

            /**
             * Check the unique user information
             */
            // Route::get('username/exist',[UserController::class,'checkUsername']);
            // Route::get('phone/exist',[UserController::class,'checkPhone']);
            // Route::get('email/exist',[UserController::class,'checkEmail']);
            // Route::post('upload',[UserController::class,'upload']);
    });


    require( 'authcenter/attendant.php');
    require( 'authcenter/regulator.php');
    require( 'authcenter/folder.php');
    require( 'authcenter/meeting.php' );
    require( 'authcenter/officer.php' );
    require( 'authcenter/countesy.php' );
    require( 'authcenter/organization.php' );
    require( 'authcenter/position.php' );
    require( 'authcenter/certificate.php' );
    require( 'authcenter/birthcertificate.php' );
    require( 'authcenter/archeivement.php' );
    require( 'authcenter/weddingcertificate.php' );
    require( 'authcenter/location.php' );
    require( 'authcenter/niccertificate.php');
    require( 'authcenter/passport.php');
    require( 'authcenter/spokenlanguage.php');
    require( 'authcenter/officerjobbackground.php');
    require( 'authcenter/officerrank.php');
    require( 'authcenter/officerrankbyworking.php');
    require( 'authcenter/officerrankbycertificate.php');
    require( 'authcenter/rank.php');
    require( 'authcenter/officerpenaltyhistory.php');
    require( 'authcenter/officermedalhistory.php');
    require( 'authcenter/officerpendingwork.php');
    require( 'authcenter/documenttransaction.php');
    require( 'authcenter/dashboard.php');
});
