<?php 
use App\Http\Controllers\Api\Admin\Officer\RankController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'ranks' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[RankController::class,'index']);
});
