<?php 
use App\Http\Controllers\Api\AuthenticationCenter\People\ArcheivementController;
/** Archeivement SECTION */
Route::group([
  'prefix' => 'archeivements' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[ArcheivementController::class,'index']);
      Route::post('',[ArcheivementController::class,'create']);
      Route::put('',[ArcheivementController::class,'update']);
      Route::get('{id}/read',[ArcheivementController::class,'read']);

      Route::delete('',[ArcheivementController::class,'destroy']);
      Route::post('upload',[ArcheivementController::class,'upload']);
      Route::get('pdf',[ArcheivementController::class,'pdf']);
});
