<?php 
use App\Http\Controllers\Api\Admin\People\NicCertificateController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'niccertificates' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[NicCertificateController::class,'index']);
      Route::post('',[NicCertificateController::class,'create']);
      Route::put('',[NicCertificateController::class,'update']);
      Route::get('{id}/read',[NicCertificateController::class,'read']);

      Route::delete('',[NicCertificateController::class,'destroy']);
      Route::post('upload',[NicCertificateController::class,'upload']);
      Route::get('pdf',[NicCertificateController::class,'pdf']);
});
