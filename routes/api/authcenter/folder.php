<?php
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\FolderController;
/** FOLDER SECTION */
  Route::group([
    'prefix' => 'folders' ,
    'middleware' => 'auth:api'
    ], function() {

        Route::get('',[ FolderController::class , 'index']);
        Route::post('',[ FolderController::class , 'create']);
        Route::get('{id}/read',[ FolderController::class , 'read']);
        Route::put('',[ FolderController::class , 'update']);
        Route::delete('',[ FolderController::class , 'delete']);
        Route::get('regulators',[ FolderController::class , 'regulators']);
        Route::put('regulators/add',[ FolderController::class , 'addRegulatorToFolder']);
        Route::put('regulators/remove',[ FolderController::class , 'removeRegulatorFromFolder']);
        Route::put('regulators/check',[ FolderController::class , 'checkRegulator']);
        Route::get('user',[ FolderController::class , 'user']);
        Route::get('list/regulator/validation',[ FolderController::class , 'listFolderWithRegulatorValidation']);

        Route::put('{id}/accessibility',[FolderController::class,'accessibility']);
        
  });
  /** FOLDER SECTION */
  Route::group([
    'prefix' => 'folders' ,
    'middleware' => 'api'
    ], function() {
      Route::get('regulators',[ FolderController::class , 'regulators']);
      Route::get('global',[ FolderController::class , 'globalFolder']);
        
  });