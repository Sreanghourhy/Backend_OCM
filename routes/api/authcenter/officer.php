<?php

use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerContractedController;
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerUnofficialController;
use App\Http\Controllers\Api\AuthenticationCenter\OfficerController;
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerJobController;
use App\Http\Controllers\Api\AuthenticationCenter\OfficerProfileController;

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
    // Route::get('{id}/read',[OfficerController::class,'read']);
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
    Route::put('updateprofile',[OfficerController::class,'updateOfficerProfile']);
    Route::put('update_work_history',[OfficerController::class,'updateWorkHistory']);
    
    /**
     * Delete a record
     */
    Route::delete('{id}/delete',[OfficerController::class,'delete']);

    /**
     * Activate, Deactivate account
     */
    Route::put('activate',[OfficerController::class,'activate']);
    Route::put('deactivate',[OfficerController::class,'deactivate']);

    /**
     * Officer Job 
     */
    // Route::get('job', [OfficerJobController::class, 'index']);
    Route::post('job/add', [OfficerJobController::class, 'addOfficeJob']);
    Route::get('job/{id}/read', [OfficerJobController::class, 'read']);
    Route::put('job/update', [OfficerJobController::class, 'updateOfficerJob']);
    Route::delete('job/{id}/destroy', [OfficerJobController::class, 'destroyOfficerJob']);
    // Route::get('/users/{id}', [OfficerJobController::class, 'show']);

    /**
     * admin_unofficial Officer 
     */
    Route::get('admin_unofficial', [OfficerUnofficialController::class, 'index']);
    Route::get('admin_unofficial/{id}/read', [OfficerUnofficialController::class, 'getOfficerUnofficialByIds']);
    Route::post('admin_unofficial/add', [OfficerUnofficialController::class, 'storeUnofficialOfficer']);
    Route::put('admin_unofficial/update', [OfficerUnofficialController::class, 'update']);
    Route::delete('admin_unofficial/{id}/delete', [OfficerUnofficialController::class, 'destroy']);
    //officer image
    Route::post('admin_unofficial/upload', [OfficerUnofficialController::class, 'uploadProfile']);

    /**
     * admin_contract Officer 
     */
    Route::get('contracted_officer', [OfficerContractedController::class, 'index']);
    Route::get('contracted_officer/{id}/read', [OfficerContractedController::class, 'getOfficerContractedByIds']);
    Route::post('contracted_officer/add', [OfficerContractedController::class, 'storeContractedOfficer']);
    Route::put('contracted_officer/update', [OfficerContractedController::class, 'update']);
    Route::delete('contracted_officer/{id}/delete', [OfficerContractedController::class, 'destroy']);
    //officer image
    Route::post('contracted_officer/upload', [OfficerContractedController::class, 'uploadProfile']);
    
    //Officer Profile
    Route::put('updateprofile',[OfficerProfileController::class,'updatePropleProfile']);
    Route::put('updatebirthcert',[OfficerProfileController::class,'updateBirthCertificate']);
    Route::put('updateweddingcert',[OfficerProfileController::class,'updateWeddingCertificate']);
    Route::put('updateparentinfo',[OfficerProfileController::class,'updateParentInformation']);
    Route::put('updateemmergency',[OfficerProfileController::class,'updateEmmergency']);

  Route::group([
    'prefix' => 'reports',
    'middleware' => 'auth:api'
  ], function () {
    Route::get('officersunderorganization', [OfficerController::class, 'officersOfGeneralDepartment']);
  });
});
Route::group([
  'prefix' => 'officers' ,
  'middleware' => 'api'
  ], function(){
  /**
   * Get a record with public_key
   */
  Route::get('signatures',[OfficerController::class,'officersSignatures']);
  Route::get('{key}/read',[OfficerController::class,'readPublic']);
  Route::get('{key}/publicphoto',[OfficerController::class,'publicPhoto']);
});
