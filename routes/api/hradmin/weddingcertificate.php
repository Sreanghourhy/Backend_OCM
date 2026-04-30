<?php 
use App\Http\Controllers\Api\Hradmin\People\WeddingCertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'weddingcertificates' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[WeddingCertificateController::class,'index']);
      Route::post('',[WeddingCertificateController::class,'create']);
      Route::put('',[WeddingCertificateController::class,'update']);
      Route::get('{id}/read',[WeddingCertificateController::class,'read']);

      Route::delete('',[WeddingCertificateController::class,'destroy']);
      Route::post('upload',[WeddingCertificateController::class,'upload']);
      Route::get('pdf',[WeddingCertificateController::class,'pdf']);
});
