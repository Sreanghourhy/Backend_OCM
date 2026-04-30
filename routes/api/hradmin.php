<?php 
use \App\Models\TrackPerformance;

use App\Http\Controllers\Api\Hradmin\AuthController;
use App\Http\Controllers\Api\Hradmin\UserController;
use App\Http\Controllers\Api\Hradmin\ProfileController;
use App\Http\Controllers\Api\Hradmin\MeetingController;
use App\Http\Controllers\Api\Hradmin\Folder\FolderController;
use App\Http\Controllers\Api\Hradmin\Countesy\CountesyController;
use App\Http\Controllers\Api\Hradmin\Organization\OrganizationController;
use App\Http\Controllers\Api\Hradmin\Position\PositionController;
use App\Http\Controllers\Api\Hradmin\Regulator\SearchController;
use App\Http\Controllers\Api\Hradmin\Regulator\SignatureController;
use App\Http\Controllers\Api\Hradmin\Regulator\RegulatorController;


/** MEETING SECTION */
Route::group([
  'prefix' => 'hradmin' ,
  'namespaces' => '\App\Http\Controllers\Api\Hradmin' ,
  'middleware' => 'api'
  ], function() {

    /** SIGNING SECTION */
    Route::group([
      'prefix' => 'authentication'
    ], function () {
      Route::post('login', [AuthController::class,'login']);

      Route::group([
        'middleware' => 'auth:api'
      ], function() {
          Route::post('logout', [AuthController::class,'logout']);
          Route::get('user', [AuthController::class,'user']);
          Route::put('password/change',[UserController::class,'passwordChange']);
      });
    });

    /** USER/ACCOUNT SECTION */
    Route::group([
      'prefix' => 'users' ,
      'middleware' => 'auth:api'
      ], function() {
        /**
         * Api for cin
         */
        Route::get('',[UserController::class,'index']);
        Route::post('',[UserController::class,'index']);
        Route::put('',[UserController::class,'update']);
        Route::get('{id}',[UserController::class,'read']);
        Route::delete('',[UserController::class,'destroy']);
        Route::put('activate',[UserController::class,'active']);
        // Route::put('password/change',[UserController::class,'logout']);
        Route::post('upload',[UserController::class,'upload']);
        Route::put('officer/update',[UserController::class,'officerUpdate']);
        Route::put('people/update',[UserController::class,'peopleUpdate']);
    });

    Route::group([
      'prefix' => 'users' ,
      'middleware' => 'api'
        ], function() {
        Route::get('checkidentification/{term}/{type}',[UserController::class,'checkIdentification']);
    });

    Route::group([
      'prefix' => 'users/authenticated' ,
      'middleware' => 'auth:api'
      ], function() {
        /**
         * Api for profile
         */
            Route::get('',[ProfileController::class,'getAuthUser']);
            Route::put('',[ProfileController::class,'updateAuthUser']);
            Route::put('password',[ProfileController::class,'updateAuthUserPassword']);
            Route::post('picture/upload',[ProfileController::class,'upload']);
    });

    /** ORGANIZATION SECTION */
    Route::group([
      'prefix' => 'organizations' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[OrganizationController::class,'index']);
    });
    /** REGULATOR SECTION */
    Route::group([
      'prefix' => 'regulators' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[RegulatorController::class,'index']);
          Route::get('pdf',[RegulatorController::class,'pdf']);
          Route::post('create',[RegulatorController::class,'create']);
          Route::put('update',[RegulatorController::class,'update']);
          Route::put('activate',[RegulatorController::class,'activate']);
          Route::put('deactivate',[RegulatorController::class,'deactivate']);
          Route::put('toggleactive',[RegulatorController::class,'toggleActive']);
          Route::delete('',[RegulatorController::class,'destroy']);
          Route::post('upload',[RegulatorController::class,'upload']);
          Route::post('upload/picture',[RegulatorController::class,'uploadPicture']);
          Route::post('upload/pdf',[RegulatorController::class,'uploadPdf']);
          Route::get('pdf',[RegulatorController::class,'pdf']);

          Route::put('addreader',[RegulatorController::class,'addReaders']);
          Route::put('removereader',[RegulatorController::class,'removeReaders']);
          Route::put('{id}/accessibility',[RegulatorController::class,'accessibility']);

    });


    /** SEARCH SECTION */
    Route::group([
      'prefix' => 'search_regulators' ,
      ], function() {
        TrackPerformance::start('clientSearchRegulator');
        Route::get('',[ SearchController::class , 'index']);
        TrackPerformance::end('clientSearchRegulator');
        TrackPerformance::save();
        // Route::get('',function(){
        //   return 'I am "regulators->SearchController"';
        // });
        Route::get('pdf',[ SearchController::class , 'pdf']);
        Route::get('get/regulator/years',[ SearchController::class , 'getYears']);
        Route::group([
            'prefix' => 'types' ,
            ], function() {
                Route::get('compact', [ TypeController::class , 'index']);
        });
        Route::get('types/compact', [ TypeController::class , 'compactList']);
        Route::get('{id}',[ RegulatorController::class , 'read']);

    });

    /** SEARCH SECTION */
    Route::group([
      'prefix' => 'regulators' ,
      'namespace' => 'Api' ,
      'middleware' => 'api'
      ], function() {
        Route::get('',[RegulatorController::class,'index']);
        Route::get('pdf',[RegulatorController::class,'pdf']);
        Route::group([
            'prefix' => 'types' ,
            ], function() {
              Route::get('', [TypeController::class,'index']);
        });
        Route::group([
          'prefix' => 'organizations' ,
          ], function() {
            Route::get('', [OrganizationController::class,'index']);
        });
        Route::group([
          'prefix' => 'signatures' ,
          ], function() {
            Route::get('', [SignatureController::class,'index']);
        });
    });

    /** FOLDER SECTION */
    Route::group([
      'prefix' => 'folders' ,
      'middleware' => 'auth:api'
      ], function() {

          Route::get('',[ FolderController::class , 'index']);
          Route::post('create',[ FolderController::class , 'create']);
          Route::get('{id}/read',[ FolderController::class , 'read']);
          Route::put('update',[ FolderController::class , 'update']);
          Route::delete('delete',[ FolderController::class , 'delete']);
          Route::put('activate',[FolderController::class,'active']);
          Route::put('deactivate',[FolderController::class,'unactive']);
          Route::put('toggleactive',[FolderController::class,'toggleActive']);
          Route::post('upload/picture',[FolderController::class,'uploadPicture']);
          Route::post('upload/pdf',[FolderController::class,'uploadPdf']);
          Route::get('pdf',[FolderController::class,'pdf']);

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

    /** TYPE SECTION */
    Route::group([
      'prefix' => 'types' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[TypeController::class,'index']);
    });

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
          Route::get('children',[PositionController::class,'getChildren']);
          Route::get('regulators',[PositionController::class,'getRegulators']);
          Route::get('staffs',[ PositionController::class , 'staffs']);
          Route::get('{id}/people',[ PositionController::class , 'people']);
          Route::put('setleader',[ PositionController::class , 'setLeader']);
          Route::put('addstaff',[ PositionController::class , 'addPeopleToPosition']);
    });

    /** COUNTESY SECTION */
    Route::group([
      'prefix' => 'countesies' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[CountesyController::class,'index']);
    });

    require('hradmin/dashboard.php');
    require('hradmin/task.php');
    require('hradmin/attendant.php');
    require('hradmin/organization.php');
    require('hradmin/countesy.php');
    require('hradmin/officer.php');
    require('hradmin/officerrank.php');
    require( 'hradmin/certificate.php' );
    require( 'hradmin/birthcertificate.php' );
    require( 'hradmin/weddingcertificate.php' );
    require( 'hradmin/location.php' );
    require( 'hradmin/niccertificate.php');
  }
);
