<?php
use App\Http\Controllers\Api\AuthenticationCenter\Organization\OrganizationController;
use App\Http\Controllers\Api\AuthenticationCenter\Organization\OrganizationStructurePositionController;
use App\Http\Controllers\Api\AuthenticationCenter\Officer\OfficerJobController;
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

    Route::get('getministry',[OrganizationController::class,'getByOwnerShipTypeSorted']);
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
    Route::delete('structure/{id}/delete',[OrganizationController::class,'deleteStructureNode']);

    /**
     * Position Structure
     */
    Route::get('position',[OrganizationController::class,'getPositions']);
    Route::post('position/add',[OrganizationController::class,'addPosition']);
    Route::delete('position/{id}/delete',[OrganizationController::class,'deletePositionNode']);
    Route::put('position/permission/toggle',[ OrganizationController::class , 'positionPermissionToggle']);


    /**
     * Get Organization by industries Endpoint
     */
    Route::get('getconsultationgroup',[OrganizationController::class,'getConsultationGroup']);
    Route::get('getsecretariatofstateoffice',[OrganizationController::class,'getSecretariatOfStateOffice']);
    Route::get('getauthority',[OrganizationController::class,'getAuthority']);
    Route::get('getcouncil',[OrganizationController::class,'getCouncil']);
    Route::get('getministry',[OrganizationController::class,'getMinistry']);
    Route::get('getgeneraldepartment',[OrganizationController::class,'getGeneralDepartment']);
    Route::get('getdepartment',[OrganizationController::class,'getDepartment']);
    Route::get('getteamwork',[OrganizationController::class,'getTeamwork']);
    //Get Heirarchy
    Route::get('listorganizationbyindustries',[OrganizationController::class,'getOrganizationsOrderByIndustries']);
    Route::get('listorganizationbyconsultation',[OrganizationController::class,'getHierarchyFromConsultationGroup']);
    Route::get('listorganizationbyauthority',[OrganizationController::class,'getHierarchyFromAuthority']);
    Route::get('listorganizationbycouncil',[OrganizationController::class,'getHierarchyFromCouncil']);
    Route::get('listorganizationbyministry',[OrganizationController::class,'getHierarchyFromMinistry']);
    Route::get('listorganizationbygeneraldepartment',[OrganizationController::class,'getHierarchyFromGeneralDepartment']);
    Route::get('listorganizationbydepartment',[OrganizationController::class,'getHierarchyFromDepartment']);
    Route::get('listorganizationbydivision',[OrganizationController::class,'getHierarchyFromDivision']);
    
    // Get position base on Organization Structure positon
    Route::get('listallposition',[OrganizationStructurePositionController::class,'getAllPosition']);

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
