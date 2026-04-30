<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Location\ProvinceController;
use App\Http\Controllers\Api\AuthenticationCenter\Location\DistrictController;
use App\Http\Controllers\Api\AuthenticationCenter\Location\CommuneController;
use App\Http\Controllers\Api\AuthenticationCenter\Location\VillageController;

Route::group([
    'prefix' => 'provinces' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('',[ProvinceController::class,'index']);
        Route::get('pdcv',[ProvinceController::class,'pdcv']);
});

Route::group([
    'prefix' => 'districts' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('',[DistrictController::class,'index']);
});

Route::group([
    'prefix' => 'communes' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('',[CommuneController::class,'index']);
});

Route::group([
    'prefix' => 'villages' ,
    'middleware' => 'auth:api'
    ], function() {
        Route::get('',[VillageController::class,'index']);
});
