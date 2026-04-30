<?php 
use App\Http\Controllers\Api\Admin\OfficerController;
/** Officer SECTION */
Route::group([
  'prefix' => 'officers' ,
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
     * Get all records
     */
    Route::get('',[OfficerController::class,'index']);
    Route::get('{id}/mybackground',[OfficerController::class,'mybackground']);
    /**
     * Create a record
     */
    Route::post('create',[OfficerController::class,'storeOfficer']);
    Route::post('createnonofficer',[OfficerController::class,'storeNonOfficer']);
    /**
     * Update a reccord with id
     */
    Route::put('update',[OfficerController::class,'update']);
    /**
     * Delete a record
     */
    Route::delete('{id}/delete',[OfficerController::class,'destroy']);

    /**
     * Activate, Deactivate account
     */
    Route::put('activate',[OfficerController::class,'activate']);
    Route::put('deactivate',[OfficerController::class,'deactivate']);
});
Route::group([
  'prefix' => 'officers' ,
  'middleware' => 'api'
  ], function(){
  /**
   * Get a record with public_key
   */
  Route::get('{key}/read',[OfficerController::class,'readPublic']);
  Route::get('{key}/publicphoto',[OfficerController::class,'publicPhoto']);
});