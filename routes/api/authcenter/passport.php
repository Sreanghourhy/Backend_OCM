<?php 
use App\Http\Controllers\Api\AuthenticationCenter\People\PassportCertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'passports' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[PassportCertificateController::class,'index']);
      Route::post('',[PassportCertificateController::class,'create']);
      Route::put('',[PassportCertificateController::class,'update']);
      Route::get('{id}/read',[PassportCertificateController::class,'read']);

      Route::delete('',[PassportCertificateController::class,'destroy']);
      Route::post('upload',[PassportCertificateController::class,'upload']);
      Route::get('pdf',[PassportCertificateController::class,'pdf']);
});
