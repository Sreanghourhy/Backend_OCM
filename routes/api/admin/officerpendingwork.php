<?php 
use App\Http\Controllers\Api\Admin\Officer\OfficerPendingWorkController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerpendingwork' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerPendingWorkController::class,'index']);
      Route::post('',[OfficerPendingWorkController::class,'create']);
      Route::put('',[OfficerPendingWorkController::class,'update']);
      Route::get('{id}/read',[OfficerPendingWorkController::class,'read']);

      Route::delete('',[OfficerPendingWorkController::class,'destroy']);
      Route::post('upload',[OfficerPendingWorkController::class,'upload']);
      Route::get('pdf',[OfficerPendingWorkController::class,'pdf']);
});
