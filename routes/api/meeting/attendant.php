<?php 
use App\Http\Controllers\Api\Meeting\Attendant\AttendantController;
/** Task SECTION */
Route::group([
  'prefix' => 'attendants' ,
  'middleware' => 'auth:api'
  ], function() {
    /**
     * Methods to apply for each of the CRUD operations
     * Create => POST
     * Read => GET
     * Update => PUT
     * Delete => DELETE
     */
    /**
     * Crud
     */
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