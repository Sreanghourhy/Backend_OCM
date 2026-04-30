<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerMedalHistoryController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officermedalhistories' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerMedalHistoryController::class,'index']);
      Route::post('',[OfficerMedalHistoryController::class,'create']);
      Route::put('',[OfficerMedalHistoryController::class,'update']);
      Route::get('{id}/read',[OfficerMedalHistoryController::class,'read']);

      Route::delete('',[OfficerMedalHistoryController::class,'destroy']);
      Route::post('upload',[OfficerMedalHistoryController::class,'upload']);
      Route::get('pdf',[OfficerMedalHistoryController::class,'pdf']);
});
