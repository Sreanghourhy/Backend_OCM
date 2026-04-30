<?php 
use App\Http\Controllers\Api\Admin\Regulator\OrganizationController;
/** ORGANIZATION SECTION */
Route::group([
  'prefix' => 'organizations' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OrganizationController::class,'index']);
      Route::get('compact',[OrganizationController::class,'compact']);
      Route::get('listbyparent',[OrganizationController::class,'listByParent']);
      Route::post('create',[OrganizationController::class,'store']);
      Route::post('addchild',[OrganizationController::class,'addChild']);
      Route::put('update',[OrganizationController::class,'update']);
      Route::get('{id}/read',[OrganizationController::class,'read']);
      Route::delete('{id}/delete',[OrganizationController::class,'destroy']);
      Route::put('activate',[OrganizationController::class,'active']);
      Route::put('deactivate',[OrganizationController::class,'unactive']);
      /**
       * Check the unique user information
       */
      Route::get('children',[OrganizationController::class,'getChildren']);
      Route::get('regulators',[OrganizationController::class,'getRegulators']);
      Route::get('staffs',[ OrganizationController::class , 'staffs']);
      Route::get('{id}/people',[ OrganizationController::class , 'people']);
      Route::put('setleader',[ OrganizationController::class , 'setLeader']);
      Route::put('addstaff',[ OrganizationController::class , 'addPeopleToOrganization']);
});
Route::group([
  'prefix' => 'organizations' ,
  'namespace' => 'Api' ,
  'middleware' => 'api'
  ], function() {
  Route::get('{id}/read',[OrganizationController::class,'read']);
});
