<?php 
use App\Http\Controllers\Api\AuthenticationCenter\People\CertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'certificates' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[CertificateController::class,'index']);
      Route::post('',[CertificateController::class,'create']);
      Route::put('',[CertificateController::class,'update']);
      Route::get('{id}/read',[CertificateController::class,'read']);

      Route::delete('',[CertificateController::class,'destroy']);
      Route::post('upload',[CertificateController::class,'upload']);
      Route::get('pdf',[CertificateController::class,'pdf']);

      Route::get('groups',[CertificateController::class,'groups']);
});
