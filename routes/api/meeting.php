<?php 
use \App\Models\TrackPerformance;

use App\Http\Controllers\Api\Meeting\AuthController;
use App\Http\Controllers\Api\Meeting\UserController;
use App\Http\Controllers\Api\Meeting\ProfileController;
use App\Http\Controllers\Api\Meeting\MeetingController;
use App\Http\Controllers\Api\Meeting\Folder\FolderController;
use App\Http\Controllers\Api\Meeting\Type\MeetingTypeController;
use App\Http\Controllers\Api\Meeting\Room\RoomController;
use App\Http\Controllers\Api\Meeting\Room\MeetingRoomController;
use App\Http\Controllers\Api\Meeting\LegalDraft\LegalDraftController;
use App\Http\Controllers\Api\Meeting\Member\MemberController;
use App\Http\Controllers\Api\Meeting\Countesy\CountesyController;
use App\Http\Controllers\Api\Meeting\Organization\OrganizationController;
use App\Http\Controllers\Api\Meeting\Position\PositionController;
use App\Http\Controllers\Api\Meeting\Regulator\SearchController;
use App\Http\Controllers\Api\Meeting\Regulator\SignatureController;
use App\Http\Controllers\Api\Meeting\Regulator\RegulatorController;


/** MEETING SECTION */
Route::group([
  'prefix' => 'meeting' ,
  'namespaces' => '\App\Http\Controllers\Api\Meeting' ,
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

    /** LEGAL DRAFT SECTION */
    Route::group([
      'prefix' => 'legaldrafts' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {;
          Route::get('',[LegalDraftController::class,'index']);
          Route::get('read',[LegalDraftController::class,'read']);
          Route::post('',[LegalDraftController::class,'create']);
          Route::put('',[LegalDraftController::class,'update']);
          Route::delete('',[LegalDraftController::class,'destroy']);
          // Route::post('upload',[LegalDraftController::class,'upload']);
    });

    /**
     * Methods to apply for each of the CRUD operations
     * Create => POST
     * Read => GET
     * Update => PUT
     * Delete => DELETE
     */
    /**
     * Crud
     */
    Route::group([
      'prefix' => 'meetings' ,
      'middleware' => 'auth:api'
      ], function() {
      Route::get('',[MeetingController::class,'index']);
      Route::post('create',[MeetingController::class,'create']);
      Route::put('update',[MeetingController::class,'update']);
      Route::get('{id}/read',[MeetingController::class,'read']);
      // Route::delete('{id}/delete',[MeetingController::class,'destroy']);
      Route::put('toggleactive',[MeetingController::class,'toggleActive']);
      Route::delete('delete',[MeetingController::class,'destroy']);
      Route::put('start',[MeetingController::class,'start']);
      Route::put('end',[MeetingController::class,'end']);
      // Route::post('upload/pdf',[RegulatorController::class,'uploadPdf']);
      Route::post('upload/pdf',[MeetingController::class,'uploadPdf']);
      /**
       * Meeting history
       */
      Route::get('{id}/history',[MeetingController::class,'history']);
      /**
       * Status
       */
      Route::put('{id}/status/new',[MeetingController::class,'statusNew']);
      Route::put('{id}/status/meeting',[MeetingController::class,'statusMeeting']);
      Route::put('{id}/status/continue',[MeetingController::class,'statusContinue']);
      Route::put('{id}/status/change',[MeetingController::class,'statusChange']);
      Route::put('{id}/status/delay',[MeetingController::class,'statusDelay']);
      Route::put('{id}/status/finished',[MeetingController::class,'statusFinished']);
      /**
       * Reference files
       */
      Route::post('upload/ministerpreengs',[MeetingController::class,'uploadMinisterSeichdeyPreengs']);
      Route::get('{id}/read/ministerpreeng/{serial}',[MeetingController::class,'readMinisterPdfPreeng']);
      Route::get('{id}/remove/ministerpreeng/{serial}',[MeetingController::class,'removeMinisterPdfPreeng']);

      Route::post('upload/preengs',[MeetingController::class,'uploadSeichdeyPreengs']);
      Route::get('{id}/read/preeng/{serial}',[MeetingController::class,'readPdfPreeng']);
      Route::get('{id}/remove/preeng/{serial}',[MeetingController::class,'removePdfPreeng']);
      
      Route::post('upload/reports',[MeetingController::class,'uploadReports']);
      Route::get('{id}/read/report/{serial}',[MeetingController::class,'readPdfReport']);
      Route::get('{id}/remove/report/{serial}',[MeetingController::class,'removePdfReport']);

      Route::post('upload/techreports',[MeetingController::class,'uploadTechReports']);
      Route::get('{id}/read/techreport/{serial}',[MeetingController::class,'readPdfTechReport']);
      Route::get('{id}/remove/techreport/{serial}',[MeetingController::class,'removePdfTechReport']);

      Route::post('upload/otherdocuments',[MeetingController::class,'uploadOtherDocuments']);
      Route::get('{id}/read/otherdocument/{serial}',[MeetingController::class,'readPdfOtherDocument']);
      Route::get('{id}/remove/otherdocument/{serial}',[MeetingController::class,'removePdfOtherDocument']);

      /**
       * Organization
       */
      Route::put('{id}/organization/{oid}/toggle',[MeetingController::class,'toggleOrganization']);
      /**
       * Regulator
       */
      Route::put('{id}/regulator/{rid}/toggle',[MeetingController::class,'toggleRegulator']);
      /**
       * Member
       */
      Route::put('{id}/member/{mid}/toggle',[MeetingController::class,'toggleMember']);
      Route::post('update_group_role',[MeetingController::class,'updateMemberGroupAndRole']);
      /**
       * Room
       */
      Route::put('{id}/room/{rid}/toggle',[MeetingController::class,'toggleRoom']);
      /**
       * Check attendant
       */
      Route::put('meeting_member/{meeting_member_id}/member/{member_id}/toggle',[MeetingController::class,'checkAttendantToggle']);
      
    });

    Route::group([
      'prefix' => 'tv' ,
      'middleware' => 'api'
      ], function() {
        Route::group([
          'prefix' => 'meetings' ,
          'middleware' => 'api'
          ], function() {
          Route::get('',[MeetingController::class,'tvMeetings']);
        });
    });

    /** ORGANIZATION SECTION */
    Route::group([
      'prefix' => 'organizations' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[OrganizationController::class,'index']);
    });
    /** PEOPLE SECTION */
    Route::group([
      'prefix' => 'people' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[MemberController::class,'index']);
          Route::post('save',[MemberController::class,'save']);
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

          // Route::group([
          //     'prefix' => 'types' ,
          //     ], function() {
          //       Route::get('', [TypeController::class,'index']);
          // });
          // Route::group([
          //   'prefix' => 'organizations' ,
          //   ], function() {
          //     Route::get('', [OrganizationController::class,'index']);
          // });
          // Route::group([
          //   'prefix' => 'signatures' ,
          //   ], function() {
          //     Route::get('', [SignatureController::class,'index']);
          // });

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
          Route::get('',[MeetingTypeController::class,'index']);
    });
    /** POSITIONS SECTION */
    Route::group([
      'prefix' => 'positions' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[PositionController::class,'index']);
    });
    /** COUNTESY SECTION */
    Route::group([
      'prefix' => 'countesies' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[CountesyController::class,'index']);
    });
    /** ROOM SECTION */
    Route::group([
      'prefix' => 'rooms' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::post('create',[ RoomController::class , 'create']);
          Route::get('{id}/read',[ RoomController::class , 'read']);
          Route::put('update',[ RoomController::class , 'update']);
          Route::delete('delete',[ RoomController::class , 'delete']);

          Route::get('',[RoomController::class,'index']);
          Route::get('pdf',[RoomController::class,'pdf']);
          Route::put('toggleactive',[RoomController::class,'toggleActive']);
          Route::post('upload/picture',[RoomController::class,'uploadPicture']);
          Route::post('upload/pdf',[RoomController::class,'uploadPdf']);
          Route::get('pdf',[RoomController::class,'pdf']);
    });
    /** MEETING ROOM SECTION */
    Route::group([
      'prefix' => 'meetingrooms' ,
      'namespace' => 'Api' ,
      'middleware' => 'auth:api'
      ], function() {
          Route::get('',[MeetingRoomController::class,'index']);
    });

    require('meeting/dashboard.php');
    require('meeting/task.php');
    require('meeting/attendant.php');

  }
);