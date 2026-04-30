<?php
use App\Http\Controllers\Api\Admin\Organization\OrganizationController;
use App\Http\Controllers\Api\Admin\Organization\OrganizationStructurePositionController;
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
      Route::get('positions',[OrganizationController::class,'getPositions']);
      Route::get('structure_organization',[OrganizationController::class,'getStructureOrganizations']);
      /**
       * Structure
       */
      Route::get('structure',[OrganizationController::class,'getStructure']);
      Route::post('structure/add',[OrganizationController::class,'addStructure']);
      Route::post('structure/move',[OrganizationController::class,'moveStructure']);
      Route::delete('structure/{id}/delete',[OrganizationController::class,'deleteStructureNode']);

      /**
       * Position Structure
       */
      Route::get('position',[OrganizationController::class,'getPositions']);
      Route::post('position/add',[OrganizationController::class,'addPosition']);
      Route::delete('position/{id}/delete',[OrganizationController::class,'deletePositionNode']);
      Route::put('position/permission/toggle',[ OrganizationController::class , 'positionPermissionToggle']);

      /**
       * Get Data from organization structure
       */
      Route::get('flattree',[OrganizationController::class,'organizationStructureFlatTree']);
      Route::get('nestedtree',[OrganizationController::class,'organizationStgructureNestedTree']);
});
Route::group([
  'prefix' => 'organizations' ,
  'namespace' => 'Api' ,
  'middleware' => 'api'
  ], function() {
  Route::get('{id}/read',[OrganizationController::class,'read']);
});

Route::group([
  'prefix' => 'organization_structures_position' ,
  'namespace' => 'Api' ,
  'middleware' => 'auth:api'
  ], function() {
      Route::get('',[OrganizationStructurePositionController::class,'index']);
});
