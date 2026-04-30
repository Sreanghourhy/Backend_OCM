<?php 
use App\Http\Controllers\Api\Admin\Officer\OfficerRankByWorkingController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerrankbyworkings' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerRankByWorkingController::class,'index']);
      Route::post('',[OfficerRankByWorkingController::class,'create']);
      Route::put('',[OfficerRankByWorkingController::class,'update']);
      Route::get('{id}/read',[OfficerRankByWorkingController::class,'read']);

      Route::delete('',[OfficerRankByWorkingController::class,'destroy']);
      Route::post('upload',[OfficerRankByWorkingController::class,'upload']);
      Route::get('pdf',[OfficerRankByWorkingController::class,'pdf']);
});
