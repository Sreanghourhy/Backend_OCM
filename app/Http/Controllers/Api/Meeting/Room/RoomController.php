<?php

namespace App\Http\Controllers\Api\Meeting\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\Meeting\Room as RecordModel;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class RoomController extends Controller
{
    private $model = null ;
    private $fields = [ 'id','name','desp' , 'image' , 'pdf' , 'record_index' , 'active' ] ;

    private $renameFields = [
        'pid' => 'parentId'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        
        // $id = isset( $request->id ) && intval( $request->id ) > 0? intval( $request->id ) : false ;
        // $root = $id > 0 
        //     ? RecordModel::where('id',$id)->first()
        //     : null ;
        // if( $root != null ){
        //     $root->parentNode;
        //     $root->totalChilds = $root->totalChildNodesOfAllLevels();   
        // }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],
                // 'in' => [] ,
                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'number' ,
                //         'value' => $number === false ? "" : $number
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , [
            'image' => function($record){
                return strlen( $record->image ) > 0 && \Storage::disk('public')->exists( $record->image )
                    ? \Storage::disk('public')->url( $record->image )
                    : false ;
            } ,
            'pdf' => function($record){
                return strlen( $record->pdf ) > 0 && \Storage::disk('data')->exists( $record->pdf )
                    ? true
                    : false ;
            }
        ] , $this->renameFields );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'meetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'notStartedMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'progressMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,            
            'tobeContinuedMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'delayedMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'changedMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'finishedMeetings' => [ 'id' , 'objective' , 'start' , 'end' , 'date' , 'meeting_time' ] ,
            'organization' => [ 'id' , 'name' , 'desp' ]
        ]);

        $builder = $crud->getListBuilder();
        
        // if( $root != null && $root->id > 0 ){
        //     $builder = $builder->where('tpid', "LIKE" , ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%");
        //     $root->parentId = null ;
        // }

        $responseData = $crud->pagination(true , $builder );
        // $responseData['records'] = $responseData['records']->prepend( $root );
        $responseData['records'] = $responseData['records']->map(function($room){
            $isMeeting = false ;
            $objRoom = \App\Models\Meeting\Room::find( $room['id'] ) ;
            $room['meetingRooms'] = $objRoom != null ? (
                $objRoom->meetingRooms->map(function($room) use( &$isMeeting ){
                    $room->meeting ;
                    $room->organization ;
                    $meetingTime = $room->meeting != null ? $room->meeting->getMeetingTime() : false ;
                    $isMeeting = is_array( $meetingTime ) && intval( $meetingTime['minutes'] ) == 0 ? true : false ;
                    return [
                        'id' => $room->id ,
                        'date' => $room->date ,
                        'start' => $room->start ,
                        'end' => $room->end ,
                        'remark' => $room->remark ,
                        'is_meeting' => $isMeeting ,
                        'meeting' => $room->meeting != null ? [
                            'id' => $room->meeting->id ,
                            'date' => $room->meeting->date ,
                            'objective' => $room->meeting->objective ,
                            'start' => $room->meeting->start ,
                            'end' => $room->meeting->end ,
                            'meeting_time' => $meetingTime
                        ] : null ,
                        'organization' => $room->organization != null ? [
                            'id' => $room->organization->id ,
                            'name' => $room->organization->name ,
                            'desp' => $room->organization->desp
                        ] : null
                    ];
                }) 
            ): [] ;
            $room['is_meeting'] = $isMeeting ;
            return $room;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /** Mini display */
    public function compact(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 1000 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'model' ,
                //         'value' => ''
                //     ],
                // ],
                // 'in' => [] ,
                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'number' ,
                //         'value' => $number === false ? "" : $number
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, $this->fields );
        $responseData = $crud->pagination(true, $this->model->children()->orderby('record_index','asc') );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /**
     * Create an account
     */
    public function store(Request $request){
        // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
        $record = RecordModel::create([
            'name' => $request->name,
            'desp' => $request->desp ,
            'image' => $request->image ,
            'pid' => null ,
            'tpid' => null
        ]);

        if( $record ){
            return response()->json([
                'record' => $record ,
                'ok' => true ,
                'message' => 'បង្កើតបានរួចរាល់'
            ], 200);

        }else {
            return response()->json([
                'user' => null ,
                'ok' => false ,
                'message' => 'មានបញ្ហា។'
            ], 201);
        }
    }
    /**
     * Create child
     */
    public function addChild(Request $request){
        $parent = intval( $request->pid ) > 0 
            ? RecordModel::find($request->pid) 
            : null ;
        if( $parent == null ){
            return response()->json([
                'ok' => false ,
                'message' => "សូមជ្រើសរើសមេជាមុនសិន។"
            ],350);
        }
        /**
         * In case the child that is going to be added is the child of the govenment
         */
        $root = null ;
        if( $parent->tpid == null || $parent->tpid <=0 ){
            $root = RecordModel::where('model', get_class( new RecordModel ) )->first();
        }
        // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
        $record = new RecordModel();
        $record->name = $request->name ;
        $record->desp = $request->desp;
        $record->image = '' ;
        $record->pid = $parent->id ;
        $record->save();
        // $record = RecordModel::create([
        //     'name' => $request->name,
        //     'desp' => $request->desp ,
        //     'image' => $request->image ,
        //     'pid' => $parent->id ,
        //     'tpid' => ''
        // ]);
        $record->tpid = ( $parent->tpid != "" ? $parent->tpid : $parent->pid ).":".$parent->id;
        $record->save();

        if( $record ){
            return response()->json([
                'record' => $record ,
                'ok' => true ,
                'message' => 'បង្កើតបានរួចរាល់'
            ], 200);

        }else {
            return response()->json([
                'user' => null ,
                'ok' => false ,
                'message' => 'មានបញ្ហា។'
            ], 201);
        }
    }
    /**
     * Create an account
     */
    public function update(Request $request){
        $record = isset( $request->id ) && $request->id > 0 ? RecordModel::find($request->id) : false ;
        if( $record ) {
            $updateData = [
                'name' => $request->name ,
                'desp' => $request->desp
            ];
            intval( $request->pid ) > 0
                ? $updateData['pid'] = $request->pid
                : false ;
            $record->update( $updateData );
            return response()->json([
                'record' => $record ,
                'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                'ok' => true
            ], 200);
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'record' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
                'ok' => false
            ], 403);
        }
    }
    /**
     * Active function of the account
     */
    public function active(Request $request){
        $user = RecordModel::find($request->id) ;
        if( $user ){
            $user->active = $request->active ;
            $user->save();
            // User does exists
            return response([
                'user' => $user ,
                'ok' => true ,
                'message' => 'គណនី '.$user->name.' បានបើកដោយជោគជ័យ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !' 
                ],
                201
            );
        }
    }
    /**
     * Unactive function of the account
     */
    public function unactive(Request $request){
        $user = RecordModel::find($request->id) ;
        if( $user ){
            $user->active = 0 ;
            $user->save();
            // User does exists
            return response([
                'ok' => true ,
                'user' => $user ,
                'message' => 'គណនី '.$user->name.' បានបិទដោយជោគជ័យ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !' ],
                201
            );
        }
    }
    /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->deleted_at = \Carbon\Carbon::now() ;
            $record->save();
            // User does exists
            return response([
                'ok' => true ,
                'record' => $record ,
                'message' => ' បានលុបដោយជោគជ័យ !' ,
                'ok' => true 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'record' => null ,
                'message' => 'សូមទោស ព័ត៌មាននេះមិនមានទេ !' ],
                201
            );
        }
    }
    /**
     * View the pdf file
     */
    // public function pdf(Request $request)
    // {
    //     $record = RecordModel::findOrFail($request->id);
    //     if($record) {
            
    //         $className = strtolower( get_class( $this ) ) ;

    //         $pathPdf = storage_path( 'data' ) . '/' . $record->pdf ;
    //         $ext = pathinfo($pathPdf);
    //         $filename = md5($record->id) . '.pdf';
    //         if(file_exists( $pathPdf ) && is_file($pathPdf)) {
    //             $pdfBase64 = base64_encode( file_get_contents( $pathPdf ) );  
    //             return response([
    //                 "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
    //                 "filename" => $filename,
    //                 "ok" => true 
    //             ],200);
    //         }else{
    //             return response([
    //                 'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
    //                 'path' => $pathPdf
    //             ],500 );
    //         }
    //     }
    // }
    /**
     * Active function of the account
     */
    public function toggleActive(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record != null ){
            $record->update([ 'active' => intval( $record->active ) > 0 ? 0 : 1 ]) ;
            // record does exists
            return response([
                'record' => $record ,
                'ok' => true ,
                'message' => 'ជោគជ័យ !' 
                ],
                200
            );
        }else{
            // record does not exists
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'សូមទោស មិនមានទេ !' 
                ],
                201
            );
        }
    }
    public function uploadPicture(Request $request){
        $user = \Auth::user();
        if( $user ){
            $phpFileUploadErrors = [
                0 => 'មិនមានបញ្ហាជាមួយឯកសារឡើយ។',
                1 => "ទំហំឯកសារធំហួសកំណត់ " . ini_get("upload_max_filesize"),
                2 => 'ទំហំឯកសារធំហួសកំណត់នៃទំរង់បញ្ចូលទិន្នន័យ ' . ini_get('post_max_size'),
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            ];
            if( isset( $_FILES['files'] ) && $_FILES['files']['error'] > 0 ){
                return response()->json([
                    'ok' => false ,
                    'message' => $phpFileUploadErrors[ $_FILES['files']['error'] ]
                ],403);
            }
            $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $record = RecordModel::find($request->id) ) !== null ){

                /**
                 * Create folder for storing
                 */
                $className = strtolower( get_class( $this ) ) ;
                $moduleFolder = "" ;
                if( strpos( $className , '/' ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '/' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }else if( strpos( $className , "\\" ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '\\' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }
                
                if( !\Storage::disk('public')->exists( $moduleFolder )){
                    if( !\Storage::disk('public')->makeDirectory( $moduleFolder ) ){
                        return response()->json([
                            'ok' => false ,
                            'message' => "មិនអាចបង្កើតថតឯកសារបាន។"
                        ],500);
                    }
                }

                $userFolder = $moduleFolder.'/'.$user->id ;
                $uniqeName = \Storage::disk('public')->putFile( $userFolder , new File( $_FILES['files']['tmp_name'] ) );
                $record->image = $uniqeName ;
                $record->save();
                if( $record->image != null && strlen( $record->image ) > 0 && \Storage::disk('public')->exists( $record->image ) ){
                    $record->image = \Storage::disk("public")->url( $record->image  );
                    return response([
                        'record' => $record ,
                        'ok' => true ,
                        'message' => 'ជោគជ័យ។'
                    ],200);
                }else{
                    return response([
                        'record' => $record ,
                        'ok' => false ,
                        'message' => 'មិនមានតួនាទីដែលស្វែងរកឡើយ។'
                    ],403);
                }
            }else{
                return response([
                    'ok' => false ,
                    'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់តួនាទី។'
                ],403);
            }
        }else{
            return response([
                'ok' => false ,
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function uploadPdf(Request $request){
        $user = \Auth::user();
        if( $user ){
            $phpFileUploadErrors = [
                0 => 'មិនមានបញ្ហាជាមួយឯកសារឡើយ។',
                1 => "ទំហំឯកសារធំហួសកំណត់ " . ini_get("upload_max_filesize"),
                2 => 'ទំហំឯកសារធំហួសកំណត់នៃទំរង់បញ្ចូលទិន្នន័យ ' . ini_get('post_max_size'),
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            ];
            if( isset( $_FILES['files'] ) && $_FILES['files']['error'] > 0 ){
                return response()->json([
                    'ok' => false ,
                    'message' => $phpFileUploadErrors[ $_FILES['files']['error'] ]
                ],403);
            }
            $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $record = RecordModel::find($request->id) ) !== null ){
                /**
                 * Create folder for storing
                 */
                $folderHelper = true ;
                $className = strtolower( get_class( $this ) ) ;
                if( strpos( $className , '/' ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '/' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }else if( strpos( $className , "\\" ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '\\' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }
                
                $userFolder = $moduleFolder.'/'.$user->id ;
                if( !Storage::disk('data')->exists( $userFolder )){
                    if( !Storage::disk('data')->makeDirectory( $userFolder ) ){
                        return response()->json([
                            'ok' => false ,
                            'message' => "មិនអាចបង្កើតថតឯកសារបាន។"
                        ],500);
                    }
                }
                
                $uniqeName = Storage::disk('data')->putFile( $userFolder , new File( $_FILES['files']['tmp_name'] ) );
                $record->pdf = $uniqeName ;
                $record->save();
                if( Storage::disk('data')->exists( $record->pdf ) ){
                    $record->pdf = Storage::disk('data')->url( $record->pdf  );
                    // $record->pdf = true ;
                    return response([
                        'record' => $record ,
                        'message' => 'ជោគជ័យ។'
                    ],200);
                }else{
                    return response([
                        'record' => $record ,
                        'message' => 'មិនមានតួនាទីដែលស្វែងរកឡើយ។'
                    ],403);
                }
            }else{
                return response([
                    'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់តួនាទី។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $record = RecordModel::findOrFail($request->id);
        if($record) {
            
            $className = strtolower( get_class( $this ) ) ;
            $moduleFolder = "" ;
            if( strpos( $className , '/' ) !== false ){
                $moduleFolder = implode( '/' , array_filter( explode( '/' , $className ) , function( $c ){
                    return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                } ) ) ;
            }else if( strpos( $className , "\\" ) !== false ){
                $moduleFolder = implode( '/' , array_filter( explode( '\\' , $className ) , function( $c ){
                    return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                } ) ) ;
            }
            
            if( !Storage::disk('data')->exists( $moduleFolder )){
                return response()->json([
                    'ok' => false ,
                    'message' => "មិនមានថតឯកសារនេះឡើយ។ {$moduleFolder}"
                ],500);
            }

            $pathPdf = storage_path( 'data' ) . '/' . $record->pdf ;
            $ext = pathinfo($pathPdf);
            $filename = md5($record->id) . '.pdf';
            if(file_exists( $pathPdf ) && is_file($pathPdf)) {
                $pdfBase64 = base64_encode( file_get_contents( $pathPdf ) );  
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else{
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $pathPdf
                ],500 );
            }
        }
    }
}
