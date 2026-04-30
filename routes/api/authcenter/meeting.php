<?php
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\MeetingController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\MeetingTypeController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\RoomController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\MeetingRoomController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\MemberController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\CountesyController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\OrganizationController;
use App\Http\Controllers\Api\AuthenticationCenter\Meeting\PositionController;
use App\Http\Controllers\Api\Meeting\Regulator\RegulatorController;


Route::group([
    'prefix' => 'meetings' ,
    'middleware' => 'api'
    ], function() {
    // Schedule meetings
    Route::get('schedule',[MeetingController::class,'tvMeetings']);

    Route::group([
        'middleware' => 'auth:api'
        ], function() {
        /**
         * Crud
         */
        Route::group([
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
            
        /** ORGANIZATION SECTION */
        Route::group([
        'prefix' => 'organizations'
        ], function() {
            Route::get('',[OrganizationController::class,'index']);
        });
        /** PEOPLE SECTION */
        Route::group([
        'prefix' => 'people'
        ], function() {
            Route::get('',[MemberController::class,'index']);
            Route::post('save',[MemberController::class,'save']);
        });
        /** TYPE SECTION */
        Route::group([
        'prefix' => 'types'
        ], function() {
            Route::get('',[MeetingTypeController::class,'index']);
        });
        /** POSITIONS SECTION */
        Route::group([
        'prefix' => 'positions'
        ], function() {
            Route::get('',[PositionController::class,'index']);
        });
        /** COUNTESY SECTION */
        Route::group([
        'prefix' => 'countesies'
        ], function() {
            Route::get('',[CountesyController::class,'index']);
        });
        /** ROOM SECTION */
        Route::group([
        'prefix' => 'rooms'
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
        /**
         * Regulator
         */
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
        });
    }
);