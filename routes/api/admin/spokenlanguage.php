<?php 
use App\Http\Controllers\Api\Admin\People\PeopleLanguageController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'spokenlanguage' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[PeopleLanguageController::class,'index']);
      Route::post('',[PeopleLanguageController::class,'create']);
      Route::put('',[PeopleLanguageController::class,'update']);
      Route::get('{id}/read',[PeopleLanguageController::class,'read']);

      Route::delete('',[PeopleLanguageController::class,'destroy']);
      Route::post('upload',[PeopleLanguageController::class,'upload']);
      Route::get('pdf',[PeopleLanguageController::class,'pdf']);
});
