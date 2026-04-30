<?php
use App\Http\Controllers\Api\AuthenticationCenter\OfficerProfileController;

/** Officer SECTION */
Route::group([
  'prefix' => 'officerprofile' ,
  'middleware' => 'auth:api'
  ], function() {
    Route::get('updateprofile',[OfficerProfileController::class,'updatePropleProfile']);
    Route::get('updatebirthcert',[OfficerProfileController::class,'updateBirthCertificate']);
    Route::get('updateweddingcert',[OfficerProfileController::class,'updateWeddingCertificate']);
    Route::get('updateparentinfo',[OfficerProfileController::class,'updateParentInformation']);
    Route::get('updateemmergency',[OfficerProfileController::class,'updateEmmergency']);
});