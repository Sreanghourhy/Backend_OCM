<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\CountesyController;
/** Task SECTION */
/** COUNTESY SECTION */
Route::group([
  'prefix' => 'countesies' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[CountesyController::class,'index']);
      Route::post('create',[CountesyController::class,'store']);
      Route::put('update',[CountesyController::class,'update']);
      Route::get('{id}/read',[CountesyController::class,'read']);
      Route::delete('{id}/delete',[CountesyController::class,'destroy']);
      Route::put('activate',[CountesyController::class,'active']);
});