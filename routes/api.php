<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ErrorDetailsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
  'prefix' => 'errors' ,
  'auth:api'
],function(){
  Route::get('',[ErrorDetailsController::class,'index']);
  Route::post('create',[ErrorDetailsController::class,'store']);
  Route::put('update',[ErrorDetailsController::class,'update']);
});

/**
 * API for the adimnistrator of the core system
 */
require('api/admin.php');
/**
 * API for public client
 */
require('api/client.php');
/**
 * API for meeting module
 */
require('api/meeting.php');
/**
 * Api for isolated attendant system
 */
require('api/attendant.php');
/**
 * API for Details of the regulator
 */
require('api/law.php');
/**
 * API for human resource and administration
 */
require('api/hradmin.php');
/**
 * Authentication center
 */
require('api/authcenter.php');