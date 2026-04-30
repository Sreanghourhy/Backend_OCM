<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\Law\Book\BookController;
use App\Http\Controllers\Api\Admin\Law\Book\KuntyController;
use App\Http\Controllers\Api\Admin\Law\Book\MatikaController;
use App\Http\Controllers\Api\Admin\Law\Book\ChapterController;
use App\Http\Controllers\Api\Admin\Law\Book\PartController;
use App\Http\Controllers\Api\Admin\Law\Book\SectionController;
use App\Http\Controllers\Api\Admin\Law\Book\MatraController;

/** Book SECTION */
Route::group([
  'prefix' => 'law' ,
  'middleware' => 'auth:api'
  ], function() {

  /**
   * Book Section
   */
  Route::group([
    'prefix' => 'books' ,
    ], function() {
      Route::get('', [BookController::class , 'index']);
      Route::get('{id}/read', [BookController::class , 'read']);
      Route::get('{id}/kunties', [BookController::class , 'kunties']);
      Route::get('{id}/matikas', [BookController::class , 'matikas']);
      Route::get('{id}/matras', [BookController::class , 'ofBook']);
      Route::get('exists', [BookController::class , 'exists']);
      /** Mini display */
      Route::get('compact', [BookController::class , 'compactList']);

      Route::post('', [BookController::class , 'store']);
      Route::post('{id}/save/structure', [BookController::class , 'saveStructure']);
      Route::post('removefile', [BookController::class , 'removeFile']);

      Route::put('', [BookController::class , 'update']);
      Route::post('uploadcover', [BookController::class , 'uploadCover']);
      /** Activate / Deactivate the data for using */
      Route::put('{id}/activate', [BookController::class , 'active']);
      Route::put('{id}/deactivate', [BookController::class , 'unactive']);
      Route::put('{id}/uncomplete', [BookController::class , 'uncomplete']);
      Route::put('{id}/complete', [BookController::class , 'complete']);
      Route::get('{id}/content', [BookController::class , 'content']);
      Route::delete('{id}', [BookController::class , 'delete']);

      /** User to fetch regulators and assign it as the reference document of the book. */
      Route::get('regulators',[BookController::class,'regulators']);
      Route::put('references', [BookController::class , 'references']);
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
      // Route::get('{id}/read', [ MatraController::class , function(Request $request){
      //   return response()->json(["ok"=>true, 'id' => $request->id ],200);
      // } ] );
      Route::delete('{id}/delete', [ MatraController::class , 'delete' ] );
      Route::put('exists', [ MatraController::class , 'exists' ] );

      /** Activate / Deactivate the data for using */
      Route::put('{id}/activate', [ MatraController::class , 'active' ]);
      Route::put('{id}/deactivate', [ MatraController::class , 'unactive' ]);
      /** Mini display */
      Route::get('compact', [ MatraController::class , 'compactList' ] );
  });
  /**
   * types Section
   */
  Route::group([
    'prefix' => 'types' ,
    ], function() {
      Route::get('', 'TypeController@index');
      Route::post('create', 'TypeController@store');
      Route::post('update', 'TypeController@update');
      Route::get('{id}/read', 'TypeController@read');
      Route::delete('{id}/delete', 'TypeController@delete');
      Route::get('{id}/structure', 'TypeController@structure');
      Route::post('{id}/save/structure', 'TypeController@saveStructure');

      Route::put('exists', 'TypeController@exists');

      /** Activate / Deactivate the data for using */
      Route::put('{id}/activate', 'TypeController@active');
      Route::put('{id}/deactivate', 'TypeController@unactive');

      /** Mini display */
      Route::get('compact', "TypeController@compactList");
  });
});