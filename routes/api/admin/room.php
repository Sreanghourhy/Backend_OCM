<?php 
use App\Http\Controllers\Api\Admin\RoomController;
/** Task SECTION */
Route::group([
  'prefix' => 'rooms' ,
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
    Route::get('',[RoomController::class,'index']);
    /**
     * Get a record with id
     */
    Route::get('{id}/read',[RoomController::class,'read']);
    /**
     * Create a record
     */
    Route::post('create',[RoomController::class,'store']);
    /**
     * Update a reccord with id
     */
    Route::put('update',[RoomController::class,'update']);
    /**
     * Delete a record
     */
    Route::delete('{id}/delete',[RoomController::class,'delete']);

    /**
     * Activate, Deactivate account
     */
    Route::put('activate',[RoomController::class,'activate']);
    Route::put('deactivate',[RoomController::class,'deactivate']);

    Route::put('toggleactive',[RoomController::class,'toggleActive']);
    Route::post('upload/picture',[RoomController::class,'uploadPicture']);
    Route::post('upload/pdf',[RoomController::class,'uploadPdf']);
    Route::get('pdf',[RoomController::class,'pdf']);
    
  });