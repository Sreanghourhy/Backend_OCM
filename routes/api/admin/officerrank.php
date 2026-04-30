<?php 
use App\Http\Controllers\Api\Admin\Officer\OfficerRankController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerranks' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerRankController::class,'index']);
      Route::post('',[OfficerRankController::class,'create']);
      Route::put('',[OfficerRankController::class,'update']);
      Route::get('{id}/read',[OfficerRankController::class,'read']);

      Route::delete('',[OfficerRankController::class,'destroy']);
      Route::post('upload',[OfficerRankController::class,'upload']);
      Route::get('pdf',[OfficerRankController::class,'pdf']);
});
