<?php 
use App\Http\Controllers\Api\AuthenticationCenter\Attendant\AttendantController;
/** Attendant SECTION */
Route::group([
  'prefix' => 'attendants' ,
  'namespaces' => '\App\Http\Controllers\Api\Admin\Attendant' ,
  'middleware' => 'auth:api'
  ], function() {
    /**
     * Crud
     */
    Route::get('',[AttendantController::class,'index']);
    Route::put('update',[AttendantController::class,'update']);
    Route::get('{id}/read',[AttendantController::class,'read']);
    Route::delete('{id}/delete',[AttendantController::class,'destroy']);
    /**
     * Check in & Check out within the system
     */
    Route::post('checkin/system',[AttendantController::class,'systemCheckin']);
    Route::post('checkout/system',[AttendantController::class,'systemCheckout']);
    /**
     * Check in & Check out with face reconigtion device
     */
    Route::post('checkin/face',[AttendantController::class,'faceCheckin']);
    Route::post('checkout/face',[AttendantController::class,'faceCheckin']);
    /**
     * Check in & Check out within the finger print device
     */
    Route::post('checkout/finger',[AttendantController::class,'fingerCheckout']);
    Route::post('checkout/finger',[AttendantController::class,'finterCheckout']);
    /**
     * Check in & Check out within the staff ID
     */
    Route::post('checkin/staffid',[AttendantController::class,'staffCheckin']);
    Route::post('checkout/staffid',[AttendantController::class,'staffCheckout']);
    /**
     * Get the attendants of user
     */
    Route::get('{date}/month/{userId}/user',[AttendantController::class,'userAttendants']);
    /**
     * Get picture of attendant checktime as base64
     */
    Route::get('checktime/{id}/readphoto',[AttendantController::class,'readAttendantChecktimePhoto']);
});

  Route::group([
    'prefix' => 'attendants' ,
    'namespaces' => '\App\Http\Controllers\Api\Admin\Attendant' ,
    'middleware' => 'api'
    ], function() {
    Route::post('checkattendantbyemailorphone',[AttendantController::class,'checkAttendantByEmailOrPhoneByOrganization']);
    Route::get('getattendantbyemailorphone/{term}/{type}',[AttendantController::class,'getAttendantByEmailOrPhone']);
    
    Route::get('checkattendant/face',function(){
      libxml_use_internal_errors(true);
      $data = null;
      $url = "http://127.0.0.1:8080/face" ;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch,CURLOPT_URL, $url );
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
      $data = curl_exec($ch);
      curl_close($ch);
      if( empty($data) ) return false ;
      $doc = json_decode( $data );
      return response()->json($doc->message->verified,200);
          // img1_path: storage_path().'/data/attendants/1.jpg' ,
          // img2_path: storage_path().'/data/attendants/2.jpg'
    });
    
  });

