
<?php
use App\Models\People\People;
use App\Models\People\Certificate;
use App\Models\Officer\Officer;
use App\Models\Officer\OfficerJob;
use App\Http\Controllers\Api\AuthenticationCenter\DashboardController;

Route::group([
    'prefix' => 'dashboard',
    'middleware' => 'auth:api'
], function () {
    Route::group([
        'prefix' => 'people',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('statistics', function (Request $request) {
            return response()->json([
                'groups'=> People::getAgeGroupStatistics() ,
                'distribution' => People::getAgeDistribution() ,
                'statistics' => People::getAgeStatistics()
            ],200);
        });
    });

    Route::get('stats', [DashboardController::class, 'getStats']);

    Route::group([
        'prefix' => 'officers',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('statistics', function (Request $request) {
            return response()->json([
                'basicStats' => Officer::getCountByOfficerType(),
                'detailedStats' => Officer::getDetailedOfficerTypeStats() ,
                'comprehensiveReport' => Officer::getComprehensiveOfficerReport() ,
                'orphanedOfficers' => Officer::getOrphanedOfficers() ,
                'dashboardStats' => Officer::getOfficerDashboardStats() ,
                'detailedReport' => Officer::getDetailedCareerReport() ,
                'readyForPromotion' => Officer::getOfficersReadyForPromotion()
            ],200);
        });
    });

    Route::group([
        'prefix' => 'officerjobs',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('statistics', function (Request $request) {
            return response()->json([
                'currentOfficers' => OfficerJob::getCurrentOfficers(),
                'structureCounts' => OfficerJob::getStructureOfficerCounts(),
                'positionUtilization' => OfficerJob::getPositionUtilization(),
                'careerPaths' => OfficerJob::getOfficerCareerPaths(),
                'dashboardStats' => OfficerJob::getDashboardStats()
            ],200);
        });
    });

    Route::group([
        'prefix' => 'certificates',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('statistics', function (Request $request) {

            return response()->json([
                'educationStats' => Certificate::getEducationStatisticsByLevel(),
                'detailedEducation' => Certificate::getDetailedEducationWithGroups(),
                'educationSummary' => Certificate::getEducationSummaryByGroup(),
                'educationProgression'=> Certificate::getOfficerEducationProgression(),
                'phdOfficers' => Certificate::getOfficersByEducationLevel('PhD'),
                'mastersOfficers' => Certificate::getOfficersByEducationLevel('Master'),
                'bachelorsOfficers' => Certificate::getOfficersByEducationLevel('Bachelor')
            ],200);
        });

    });



});
