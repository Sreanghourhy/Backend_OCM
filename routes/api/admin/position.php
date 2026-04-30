<?php 
use App\Http\Controllers\Api\Admin\Position\PositionController;
/** POSITION SECTION */
Route::group([
    'prefix' => 'positions' ,
    'namespace' => 'Api' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('',[PositionController::class,'index']);
        Route::get('compact',[PositionController::class,'compact']);
        Route::get('listbyparent',[PositionController::class,'listByParent']);
        Route::post('create',[PositionController::class,'store']);
        Route::post('addchild',[PositionController::class,'addChild']);
        Route::put('update',[PositionController::class,'update']);
        Route::get('{id}/read',[PositionController::class,'read']);
        Route::delete('{id}/delete',[PositionController::class,'destroy']);
        Route::put('activate',[PositionController::class,'active']);
        Route::put('deactivate',[PositionController::class,'unactive']);
        /**
         * Check the unique user information
         */
        Route::get('structure',[PositionController::class,'getStructure']);
        /**
       * Position Structure
       */
        Route::get('position',[PositionController::class,'getPositions']);
        Route::post('position/add',[PositionController::class,'addPosition']);
        Route::delete('position/{id}/delete',[PositionController::class,'deletePositionNode']);
        Route::put('position/permission/toggle',[ PositionController::class , 'positionPermissionToggle']);
        Route::get('structure_position',[PositionController::class,'getStructurePositions']);
        
        Route::get('children',[PositionController::class,'getChildren']);
        Route::get('regulators',[PositionController::class,'getRegulators']);
        Route::get('staffs',[ PositionController::class , 'staffs']);
        Route::get('{id}/people',[ PositionController::class , 'people']);
        Route::put('setleader',[ PositionController::class , 'setLeader']);
        Route::put('addstaff',[ PositionController::class , 'addPeopleToPosition']);
});