<?php
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\SearchController;
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\TypeController;
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\RegulatorController;
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\OrganizationController;
use App\Http\Controllers\Api\AuthenticationCenter\Regulator\SignatureController;
/** SEARCH SECTION */
Route::group([
    'prefix' => 'regulators' ,
    ], function() {
    //   TrackPerformance::start('clientSearchRegulator');
      Route::get('search',[ SearchController::class , 'index']);
      Route::get('favorites', [SearchController::class,'favorites']);
    //   TrackPerformance::end('clientSearchRegulator');
    //   TrackPerformance::save();
      Route::get('pdf',[ SearchController::class , 'pdf']);
      Route::get('get/regulator/years',[ SearchController::class , 'getYears']);
      Route::get('types', [ TypeController::class , 'compact']);
      Route::get('{id}/read',[ RegulatorController::class , 'read']);
      Route::get('signatures', [SignatureController::class,'compact']);
      Route::get('organizations', [ OrganizationController::class , 'compact']);
  });