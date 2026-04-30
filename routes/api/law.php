<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
use App\Http\Controllers\Api\Law\GoogleController;
use App\Http\Controllers\Api\Law\TelegramController;
use App\Http\Controllers\Api\Law\AuthController;
use App\Http\Controllers\Api\Law\UserController;
use App\Http\Controllers\Api\Law\ProfileController;
use App\Http\Controllers\Api\Law\Book\FolderController;
use App\Http\Controllers\Api\Law\Book\BookController;
use App\Http\Controllers\Api\Law\Book\KuntyController;
use App\Http\Controllers\Api\Law\Book\MatikaController;
use App\Http\Controllers\Api\Law\Book\ChapterController;
use App\Http\Controllers\Api\Law\Book\PartController;
use App\Http\Controllers\Api\Law\Book\SectionController;
use App\Http\Controllers\Api\Law\Book\MatraController;
use App\Http\Controllers\Api\Law\Book\TelegramBotController;
use App\Http\Controllers\Api\Law\Book\FavoriteMatraController;



Route::group([
    'prefix' => 'law' ,
    'api'
  ],function(){


    /** RESET PASSWORD */
    Route::put('password/forgot',[UserController::class,'forgotPassword']);
    Route::put('password/forgot/confirm',[UserController::class,'checkConfirmationCode']);
    Route::put('password/reset',[UserController::class,'passwordReset']);
    
    /** SIGNING SECTION */
    Route::group([
      'prefix' => 'authentication'
    ], function () {
      Route::post('login', [AuthController::class,'login']);
      Route::post('signup', [AuthController::class,'signup']);
      Route::put('signup/activate', [AuthController::class,'signupActivate']);

      Route::group([
        'middleware' => 'auth:api'
      ], function() {
        Route::post('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
        Route::put('password/change',[UserController::class,'passwordChange']);
      });
    });

    Route::group([
      'prefix' => 'auth'
    ], function () {
      Route::group([
        'prefix' => 'google' ,
        'middleware' => 'api'
      ], function() {
          Route::post('signin', [GoogleController::class,'updateOrCreate']);
      });
      Route::group([
        'prefix' => 'telegram' ,
        'middleware' => 'api'
      ], function() {
        Route::post('signin', [TelegramController::class,'updateOrCreate']);
      });
    });

    Route::group([
      'prefix' => 'users/authenticated' ,
      'middleware' => 'auth:api'
    ], function() {
      Route::get('',[ProfileController::class,'getAuthUser']);
      Route::put('',[ProfileController::class,'updateAuthUser']);
      Route::post('upload',[UserController::class,'upload']);
      Route::post('picture/upload',[ProfileController::class,'upload']);
      /** User favorited matras */
      Route::get('matras', [ MatraController::class , 'ofUser' ]);
      Route::put('matras/favorite', [ UserController::class , 'favoriteMatra' ]);
      Route::get('favoriteids', [ FavoriteMatraController::class , 'getFavoriteIds' ] );
    });

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
      Route::get('matras',[ FolderController::class , 'matras']);
      Route::put('matras/toggle',[ FolderController::class , 'toggleMatra']);
      Route::get('user',[ FolderController::class , 'user']);
      Route::put('{id}/accessibility',[FolderController::class,'accessibility']);
  });
    
  Route::group([
    'prefix' => 'books' ,
    'middleware' => 'api'
  ],function(){
    Route::get('',[BookController::class,'index']);
    Route::get('{id}/read',[BookController::class,'read']);
    Route::get('{id}/kunties',[BookController::class,'kunties']);
    Route::get('{id}/matikas',[BookController::class,'matikas']);
    Route::get('{id}/chapters',[BookController::class,'chapters']);
    Route::get('{id}/parts',[BookController::class,'parts']);
    Route::get('{id}/sections',[BookController::class,'sections']);
    Route::get('{id}/matras',[BookController::class,'matras']);
    Route::get('{id}/structure',[BookController::class,'structure']);
    Route::get('{id}/content', [BookController::class , 'content']);
  });
  /**
   * Kunty Section
   */
  Route::group([
    'prefix' => 'kunties' ,
  ], function() {
    Route::get('', [  KuntyController::class , 'index' ] );
    Route::get('{id}', [  KuntyController::class , 'read' ] );
    Route::get('{id}/structure', [  KuntyController::class , 'structure' ] );
    Route::get('{id}/matikas', [  KuntyController::class , 'matikas' ] );
    Route::get('{id}/chapters', [  KuntyController::class , 'chapters' ] );
    Route::get('exists', [  KuntyController::class , 'exists' ] );
    /** Mini display */
    Route::get('compact', [  KuntyController::class , 'compactList' ] );

    Route::post('', [  KuntyController::class , 'store' ] );
    Route::post('{id}/save/structure', [  KuntyController::class , 'saveStructure' ] );

    Route::put('', [  KuntyController::class , 'update' ] );
    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [  KuntyController::class , 'active' ] );
    Route::put('{id}/deactivate', [  KuntyController::class , 'unactive' ] );

    Route::delete('{id}', [  KuntyController::class , 'delete' ] );
  });
  /**
   * Matika Section
   */
  Route::group([
    'prefix' => 'matikas' ,
  ], function() {
    Route::get('', [ MatikaController::class , 'index' ]);
    Route::get('{id}', [ MatikaController::class , 'read' ]);
    Route::get('{id}/structure', [ MatikaController::class , 'structure' ]);
    Route::get('{id}/chapters', [ MatikaController::class , 'chapters' ]);
    Route::get('exists', [ MatikaController::class , 'exists' ]);
    /** Mini display */
    Route::get('compact', [ MatikaController::class , 'compactList' ]);

    Route::post('', [ MatikaController::class , 'store' ]);
    Route::post('{id}/save/structure', [ MatikaController::class , 'saveStructure' ]);

    Route::put('', [ MatikaController::class , 'update' ]);
    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [ MatikaController::class , 'active' ]);
    Route::put('{id}/deactivate', [ MatikaController::class , 'unactive' ]);

    Route::delete('{id}', [ MatikaController::class , 'delete' ]);
  });
  /**
   * Chapter Section
   */
  Route::group([
    'prefix' => 'chapters' ,
  ], function() {
    Route::get('', [ ChapterController::class , 'index']);
    Route::get('{id}', [ ChapterController::class , 'read']);
    Route::get('{id}/structure', [ ChapterController::class , 'structure']);
    Route::get('{id}/parts', [ ChapterController::class , 'parts']);
    Route::get('exists', [ ChapterController::class , 'exists']);
    /** Mini display */
    Route::get('compact', [ ChapterController::class , 'compactList']);

    Route::post('', [ ChapterController::class , 'store']);
    Route::post('{id}/save/structure', [ ChapterController::class , 'saveStructure']);

    Route::post('update', [ ChapterController::class , 'update']);
    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [ ChapterController::class , 'active']);
    Route::put('{id}/deactivate', [ ChapterController::class , 'unactive']);

    Route::delete('{id}', [ ChapterController::class , 'delete']);
  });
  /**
   * Chapter Section
   */
  Route::group([
    'prefix' => 'parts' ,
  ], function() {
    Route::get('', [ PartController::class , 'index' ]);
    Route::get('{id}', [ PartController::class , 'read' ]);
    Route::get('{id}/structure', [ PartController::class , 'structure' ]);
    Route::get('{id}/sections', [ PartController::class , 'secttions' ]);
    Route::get('exists', [ PartController::class , 'exists' ]);
    /** Mini display */
    Route::get('compact', [ PartController::class , 'compactList' ]);

    Route::post('', [ PartController::class , 'store' ]);
    Route::post('{id}/save/structure', [ PartController::class , 'saveStructure' ]);

    Route::put('', [ PartController::class , 'update' ]);
    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [ PartController::class , 'active' ]);
    Route::put('{id}/deactivate', [ PartController::class , 'unactive' ]);

    Route::delete('{id}', [ PartController::class , 'delete' ]);
  });
  /**
   * Chapter Section
   */
  Route::group([
    'prefix' => 'sections' ,
  ], function() {
    Route::get('', [ SectionController::class , 'index' ] ); 
    Route::get('{id}', [ SectionController::class , 'read' ] ); 
    Route::get('{id}/structure', [ SectionController::class , 'structure' ] ); 
    Route::get('exists', [ SectionController::class , 'exists' ] ); 
    /** Mini display */
    Route::get('compact', [ SectionController::class , 'compactList' ] ); 

    Route::post('', [ SectionController::class , 'store' ] ); 
    Route::post('{id}/save/structure', [ SectionController::class , 'saveStructure' ] ); 

    Route::put('', [ SectionController::class , 'update' ] ); 
    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [ SectionController::class , 'active' ] ); 
    Route::put('{id}/deactivate', [ SectionController::class , 'unactive' ] ); 
    Route::delete('{id}', [ SectionController::class , 'delete' ] ); 
  });
  /**
   * Matra Section
   */
  Route::group([
    'prefix' => 'matras' ,
  ], function() {
    Route::get('', [ MatraController::class , 'index' ] );
    Route::post('', [ MatraController::class , 'store' ] );
    Route::put('', [ MatraController::class , 'update' ] );
    Route::get('{id}/read', [ MatraController::class , 'read' ] );
    Route::delete('{id}/delete', [ MatraController::class , 'delete' ] );
    Route::put('exists', [ MatraController::class , 'exists' ] );

    /** Activate / Deactivate the data for using */
    Route::put('{id}/activate', [ MatraController::class , 'active' ]);
    Route::put('{id}/deactivate', [ MatraController::class , 'unactive' ]);
    /** Mini display */
    Route::get('compact', [ MatraController::class , 'compactList' ] );
  });

  Route::group([
    'prefix' => 'telegram' ,
    'middleware' => 'api'
  ],function(){
    Route::post('webhook', [TelegramBotController::class,'handleWebhook']);
    Route::get('getupdates',[TelegramBotController::class,'getUpdates']);
  });
});