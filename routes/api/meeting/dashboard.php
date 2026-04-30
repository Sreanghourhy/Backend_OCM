<?php 
use Illuminate\Http\Request;

/** DASHBOARD SECTION */
Route::group([
  'prefix' => 'dashboard' ,
  'middleware' => 'auth:api'
  ], function() {
    
    /**
     * Get total account
     */
    Route::get('users/total',function(Request $request){
        return response()->json([
            'ok' => true ,
            'result' => [
                'total' => \App\Models\User::count()
            ]
        ],200);
    })->name("DashboardTotalAccounts");

    Route::get('regulators/total',function(Request $request){
        return response()->json([
            'ok' => true ,
            'result' => [
                'total' => \App\Models\Meeting\Regulator::count()
            ]
        ],200);
    })->name("DashboardTotalRegulators");

    
    /** TOTAL MEETING SECTION */
    Route::group([
    'prefix' => 'meetings/total' ,
    'middleware' => 'auth:api'
    ], function() {
        /**
         * Get total meeting
         */
        Route::get('',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => [
                    'total' => $user != null
                        ? \App\Models\Meeting\Meeting::whereIn('created_by',[$user->id])->count()
                        : \App\Models\Meeting\Meeting::count()
                ]
            ],200);
        })->name("DashboardTotalMeetings");

        /**
         * Get total meeting by type
         */
        Route::get('bytype',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::getMeetingsByType( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByType");
        /**
         * Get total meeting by type this week
         */
        Route::get('bytype/thisweek',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::totalInThisWeek( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeThisWeek");
        /**
         * Get total meeting by type this week
         */
        Route::get('bytype/thismonth',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::totalInThisMonth( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeThisMonth");
        
        /**
         * Get total meeting by type first term
         */
        Route::get('bytype/firstterm',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::totalInFirstTerm( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeFirstTerm");

        /**
         * Get total meeting by type first semester
         */
        Route::get('bytype/firstsemester',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::totalInFirstSemester( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeFirstSemester");

        /**
         * Get total meeting by type this year
         */
        Route::get('bytype/thisyear',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::totalInThisYear( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeThisYear");

        /**
         * Get total meeting by type and by leader
         */
        Route::get('bytype/byleader',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByType( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeader");
        /**
         * Get total meeting by type and by leader
         */
        Route::get('bytype/byleader/{id}',function(Request $request){
            $user = \Auth::user();
            $leaderId = intval( $request->id ) > 0 ? $request->id : false ;
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByType( $leaderId ,  $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeader");
        /**
         * Get total meeting by type and by leader this week
         */
        Route::get('bytype/byleader/thisweek',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByTypeThisWeek( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeaderThisWeek");
        /**
         * Get total meeting by type and by leader this month
         */
        Route::get('bytype/byleader/thismonth',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByTypeThisMonth( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeaderThisMonth");
        /**
         * Get total meeting by type and by leader first term
         */
        Route::get('bytype/byleader/firstterm',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByTypeFirstTerm( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeaderFirstTerm");
        /**
         * Get total meeting by type and by leader first semester
         */
        Route::get('bytype/byleader/firstsemester',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByTypeFirstSemester( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeaderFirstSemester");
        /**
         * Get total meeting by type and by leader this year
         */
        Route::get('bytype/byleader/thisyear',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\People\People::totalMeetingsByTypeThisYear( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByTypeByLeaderThisYear");

        /**
         * Get total meeting by status
         */
        Route::get('bystatus',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Meeting::getMeetingsByStatus( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByStatus");

        /**
         * Get total meeting by organization
         */
        Route::get('byorganization',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizations( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganization");
        /**
         * Get total meeting by organization
         */
        Route::get('byorganization/{id}',function(Request $request){
            $user = \Auth::user();
            $organizationId = intval( $request->id ) > 0 ? $request->id : false ;
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizations( $organizationId , $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganization");
        /**
         * Get total meeting by organization this week
         */
        Route::get('byorganization/thisweek',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizationsThisWeek( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganizationThisWeek");
        /**
         * Get total meeting by organization this month
         */
        Route::get('byorganization/thismonth',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizationsThisMonth( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganizationThisMonth");
        /**
         * Get total meeting by organization first term
         */
        Route::get('byorganization/firstterm',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizationsFirstTerm( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganizationFirstTerm");
        /**
         * Get total meeting by organization first semester
         */
        Route::get('byorganization/firstsemester',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizationsFirstSemester( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganizationFirstSemester");
        /**
         * Get total meeting by organization this week
         */
        Route::get('byorganization/thisyear',function(Request $request){
            $user = \Auth::user();
            return response()->json([
                'ok' => true ,
                'result' => \App\Models\Meeting\Organization::getTotalMeetingsByOrganizationsThisYear( $user->id > 0 ? [$user->id] : [] )
            ],200);
        })->name("DashboardTotalMeetingsByOrganizationThisYear");

    });

});