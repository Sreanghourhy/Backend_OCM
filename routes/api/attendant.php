<?php 
use App\Http\Controllers\Api\Attendant\AttendantController;
use App\Http\Controllers\Api\Attendant\AuthController;
use App\Http\Controllers\Api\Attendant\UserController;
use App\Http\Controllers\Api\Attendant\ProfileController;
use App\Http\Controllers\Api\Attendant\OrganizationController;
/** Task SECTION */
Route::group([
  'prefix' => 'attendant' ,
  'middleware' => 'api'
  ], function() {
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
          Route::put('password/change',[UserController::class,'passwordChange']);
      });
    });

    /** USER/ACCOUNT SECTION */
    Route::group([
      'prefix' => 'users' ,
      'middleware' => 'auth:api'
      ], function() {
        /**
         * Api for cin
         */
        Route::get('',[UserController::class,'index']);
        Route::post('',[UserController::class,'index']);
        Route::put('',[UserController::class,'update']);
        Route::get('{id}',[UserController::class,'read']);
        Route::delete('',[UserController::class,'destroy']);
        Route::put('activate',[UserController::class,'active']);
        // Route::put('password/change',[UserController::class,'logout']);
        Route::post('upload',[UserController::class,'upload']);
    });

    Route::group([
      'prefix' => 'users/authenticated' ,
      'middleware' => 'auth:api'
      ], function() {
        /**
         * Api for profile
         */
            Route::get('',[ProfileController::class,'getAuthUser']);
            Route::put('',[ProfileController::class,'updateAuthUser']);
            Route::put('password',[ProfileController::class,'updateAuthUserPassword']);
            Route::post('picture/upload',[ProfileController::class,'upload']);
    });

    Route::group([
      'prefix' => 'attendants' ,
      'middleware' => 'auth:api'
      ], function() {
      Route::get('',[AttendantController::class,'index']);
      Route::put('update',[AttendantController::class,'update']);
      Route::get('{id}/read',[AttendantController::class,'read']);
      Route::delete('{id}/delete',[AttendantController::class,'destroy']);
      /**
       * Check in & Check out within the system
       */
      Route::post('checkin/system',[AttendantController::class,'authenticatedCheckin']);
      Route::post('checkout/system',[AttendantController::class,'authenticatedCheckout']);
      /**
       * Check in & Check out with face reconigtion device
       */
      Route::post('checkin/face',[AttendantController::class,'faceCheckin']);
      Route::post('checkin/face',[AttendantController::class,'faceCheckin']);
      /**
       * Check in & Check out within the finger print device
       */
      Route::post('checkout/finger',[AttendantController::class,'fingerCheckout']);
      Route::post('checkout/finger',[AttendantController::class,'finterCheckout']); 
    });

    /** ORGANIZATION SECTION */
    Route::group([
      'prefix' => 'organizations' ,
      'namespace' => 'Api' ,
      'middleware' => 'api'
      ], function() {
          Route::get('',[OrganizationController::class,'index']);
          Route::get('{id}/read',[OrganizationController::class,'read']);
    });
    
  });