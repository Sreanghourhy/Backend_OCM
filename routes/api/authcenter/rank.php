<?php
use App\Http\Controllers\Api\AuthenticationCenter\Officer\RankController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'ranks' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[RankController::class,'index']);
      Route::get('structure',[RankController::class,'structure']);
      Route::post('create', [RankController::class, 'create']);
      Route::put('update', [RankController::class, 'update']);
      Route::post('ranks/create', [RankController::class, 'create']);
      Route::put('ranks/update', [RankController::class, 'update']);

});
