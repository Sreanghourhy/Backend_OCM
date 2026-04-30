<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerJobBackgroundController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerjobbackgrounds' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerJobBackgroundController::class,'index']);
      Route::post('',[OfficerJobBackgroundController::class,'create']);
      Route::put('',[OfficerJobBackgroundController::class,'update']);
      Route::get('{id}/read',[OfficerJobBackgroundController::class,'read']);

      Route::delete('',[OfficerJobBackgroundController::class,'destroy']);
      Route::post('upload',[OfficerJobBackgroundController::class,'upload']);
      Route::get('pdf',[OfficerJobBackgroundController::class,'pdf']);
});
