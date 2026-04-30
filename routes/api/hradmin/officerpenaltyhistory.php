<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerPenaltyHistoryController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerpenaltyhistories' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerPenaltyHistoryController::class,'index']);
      Route::post('',[OfficerPenaltyHistoryController::class,'create']);
      Route::put('',[OfficerPenaltyHistoryController::class,'update']);
      Route::get('{id}/read',[OfficerPenaltyHistoryController::class,'read']);

      Route::delete('',[OfficerPenaltyHistoryController::class,'destroy']);
      Route::post('upload',[OfficerPenaltyHistoryController::class,'upload']);
      Route::get('pdf',[OfficerPenaltyHistoryController::class,'pdf']);
});
