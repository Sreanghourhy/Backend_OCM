<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerRankByCertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'officerrankbycertificates' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OfficerRankByCertificateController::class,'index']);
      Route::post('',[OfficerRankByCertificateController::class,'create']);
      Route::put('',[OfficerRankByCertificateController::class,'update']);
      Route::get('{id}/read',[OfficerRankByCertificateController::class,'read']);

      Route::delete('',[OfficerRankByCertificateController::class,'destroy']);
      Route::post('upload',[OfficerRankByCertificateController::class,'upload']);
      Route::get('pdf',[OfficerRankByCertificateController::class,'pdf']);
});