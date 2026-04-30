<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Document\TransactionController;
/** Archeivement SECTION */
Route::group([
  'prefix' => 'documents' ,
  'namespace' => 'Api' ,
  'middleware' => 'api'
  ], function() {

    Route::group([
    'prefix' => 'transactions' ,
    'namespace' => 'Api' ,
    'middleware' => 'api'
    ], function() {
      Route::get('',[TransactionController::class,'index']);
      Route::get('{id}/read',[TransactionController::class,'read']);
      Route::get('{id}/accept',[TransactionController::class,'accepted']);
      // Route::post('create',[TransactionController::class,'store']);
      Route::post('create',[TransactionController::class,'storeDraft']);
      Route::post('draft',[TransactionController::class,'storeDraft']);
      Route::post('send',[TransactionController::class,'send']);
      Route::post('update',[TransactionController::class,'update']);

      Route::delete('destroy',[TransactionController::class,'destroy']);

      Route::post('upload/word',[TransactionController::class,'uploadWord']);
      Route::post('upload/pdf',[TransactionController::class,'uploadPdf']);
      Route::post('upload/files',[TransactionController::class,'uploadFiles']);
      Route::get('download/{document_id}/pdf',[TransactionController::class,'downloadPdf']);
      Route::get('download/{document_id}/word',[TransactionController::class,'downloadWord']);
      Route::get('preview/{document_id}/pdf',[TransactionController::class,'previewPdf']);

      Route::get('getTotalByStatus',[TransactionController::class,'filterByStatus']);
  });
    
});

