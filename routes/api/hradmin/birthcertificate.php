<?php 
use App\Http\Controllers\Api\Hradmin\People\BirthCertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'birthcertificates' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[BirthCertificateController::class,'index']);
      Route::post('',[BirthCertificateController::class,'create']);
      Route::put('',[BirthCertificateController::class,'update']);
      Route::get('{id}/read',[BirthCertificateController::class,'read']);

      Route::delete('',[BirthCertificateController::class,'destroy']);
      Route::post('upload',[BirthCertificateController::class,'upload']);
      Route::get('pdf',[BirthCertificateController::class,'pdf']);
});
