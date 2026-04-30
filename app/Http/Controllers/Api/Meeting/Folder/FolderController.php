<?php

namespace App\Http\Controllers\Api\Meeting\Folder;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use App\Models\Regulator\Regulator;
use App\Models\Folder\Folder as RecordModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class FolderController extends Controller
{
    /**
     * Listing function
     */
    private $selectFields = [
        'id',
        'name' ,
        'active' ,
        'image' , 
        'pdf' ,
        'user_id' ,
        'created_at' ,
        'updated_at' ,
        'accessibility'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'user_id' ,
                        'value' => $user != null ? $user->id : false
                    ]
                ],
                // 'in' => [
                //     [
                //         'field' => 'document_type' ,
                //         'value' => isset( $request->document_type ) && $request->document_type !== null ? [$request->document_type] : false
                //     ]
                // ] ,
                // 'not' => [
                //     // [
                //     //     'field' => 'field_name' ,
                //     //     'value' => [4]
                //     // ]
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
            // "pivots" => [
            //     $unit ?
            //     [
            //         "relationship" => 'units',
            //         "where" => [
            //             "in" => [
            //                 "field" => "id",
            //                 "value" => [$request->unit]
            //             ],
            //         // "not"=> [
            //         //     [
            //         //         "field" => 'fieldName' ,
            //         //         "value"=> 'value'
            //         //     ]
            //         // ],
            //         // "like"=>  [
            //         //     [
            //         //        "field"=> 'fieldName' ,
            //         //        "value"=> 'value'
            //         //     ]
            //         // ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name'
                ]
            ],
            "order" => [
                'field' => 'name' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields, [
            'image' => function($record){
                $record->image = ( strlen( $record->image ) > 0 && \Storage::disk('public')->exists( $record->image ) )
                ? \Storage::disk('public')->url( $record->image )
                : false ;
                return $record->image ;
            },
            'pdf' => function($record){
                $record->pdf = ( strlen( $record->pdf ) > 0 && \Storage::disk('data')->exists( $record->pdf ) )
                ? true
                : false ;
                return $record->pdf ;
            }
        ]);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'regulators' => [ 'id', 'objective' , 'fid' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Listing of the global access folder
     */
    public function globalFolder(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'accessibility' ,
                        'value' => 4
                    ]
                ],
                // 'in' => [
                //     [
                //         'field' => 'document_type' ,
                //         'value' => isset( $request->document_type ) && $request->document_type !== null ? [$request->document_type] : false
                //     ]
                // ] ,
                // 'not' => [
                //     // [
                //     //     'field' => 'field_name' ,
                //     //     'value' => [4]
                //     // ]
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
            // "pivots" => [
            //     $unit ?
            //     [
            //         "relationship" => 'units',
            //         "where" => [
            //             "in" => [
            //                 "field" => "id",
            //                 "value" => [$request->unit]
            //             ],
            //         // "not"=> [
            //         //     [
            //         //         "field" => 'fieldName' ,
            //         //         "value"=> 'value'
            //         //     ]
            //         // ],
            //         // "like"=>  [
            //         //     [
            //         //        "field"=> 'fieldName' ,
            //         //        "value"=> 'value'
            //         //     ]
            //         // ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name'
                ]
            ],
            "order" => [
                'field' => 'name' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'regulators' => [ 'objective' , 'fid' ] ,
            'user' => [ 'id' , 'lastname', 'firstname' ]
        ]);

        $builder = $crud->getListBuilder();
        $builder->has('regulators'); // Get only the folder which contains some regulators
        $responseData = $crud->pagination(true, $builder);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Get Folders of a specific user which has authenticated
     */
    public function user(Request $request){

        // Create Query Builder 
        $queryBuilder = new \App\Models\Folder\Folder();

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'name', 'LIKE' , "%".$term."%") ;
                    }
                } );
            }
        }

        $queryBuilder = $queryBuilder->where('user_id', Auth::user()->id );

        $records = $queryBuilder->orderby('name','asc')->get()
                ->map( function ($record, $index) {
                    if( $record->regulators !== null ){
                        foreach( $record->regulators AS $index => $documentFolder ){
                            $documentFolder -> document ;
                            $documentFolder -> document -> type ;
                            $documentFolder -> document ->objective = strip_tags( $documentFolder -> document ->objective ) ; // clear some tags that product by the editor
                            $path = storage_path('data') . '/' . $documentFolder -> document -> pdf ; // create the link to pdf file
                            if( !is_file($path) ) $documentFolder -> document -> pdf = null ; // set the pdf link to null if it does not exist
                        }
                    }
                    return $record ;
                });

        return response([
            'ok' => true ,
            'records' => $records ,
            'message' => count( $records ) > 0 ? " មានថតឯកសារចំនូួន ៖ " . count( $records ) : "មិនមានថតឯកសារត្រូវជាមួយការស្វែងរកនេះឡើយ !"
        ],200 );
    }
    /**
     * Get Folders of a specific user which has authenticated
     * And also check the folder whether it does contain the document or not
     */
    public function listFolderWithRegulatorValidation(Request $request){

        // Create Query Builder 
        $queryBuilder = new \App\Models\Folder\Folder();

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'name', 'LIKE' , "%".$term."%") ;
                    }
                } );
            }
        }

        $queryBuilder = $queryBuilder->where('user_id', Auth::user()->id );

        $records = $queryBuilder->orderby('name','asc')->get()
                ->map( function ($record, $index) use( $request ) {
                    return [
                        'id' => $record->id ,
                        'name' => $record->name ,
                        'exists' => $record->regulators !== null ? (
                            in_array( $request->regulator_id, $record->regulators->pluck('id')->toArray() )
                        ) : false
                    ];
                });

        return response([
            'ok' => true ,
            'records' => $records ,
            'message' => count( $records ) > 0 ? " មានថតឯកសារចំនូួន ៖ " . count( $records ) : "មិនមានថតឯកសារត្រូវជាមួយការស្វែងរកនេះឡើយ !"
        ],200 );
    }
    // Save the folder 
    public function create(Request $request){
        if( $request->name != "" 
        // && Auth::user() != null 
        ){
            $folder = new \App\Models\Folder\Folder();
            $folder->name = $request->name ;
            $folder->user_id = Auth::user() != null ? Auth::user()->id : 0 ;
            $folder->pid = 0 ;
            $folder->active = 1 ;
            $folder->save() ;
            $folder->user ;
            $folder->regulators ;
            // User does exists
            return response([
                'ok' => true ,
                'record' => $folder ,
                'message' => 'កម្រងឯកសារ '.$folder->name.' បានរក្សារួចរាល់ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'សូមបញ្ចូលឈ្មោះកម្រងឯកសារជាមុនសិន !' ],
                201
            );
        }
    }
    // Update the folder 
    public function update(Request $request){
        if( ( $folder = RecordModel::find($request->id) ) != null && $request->name != "" ){
            $folder->name = $request->name ;
            $folder->save() ;
            $folder->user ;
            $folder->regulators ;
            // User does exists
            return response([
                'ok' => true ,
                'record' => $folder ,
                'message' => 'កម្រងឯកសារ '.$folder->name.' បានរក្សារួចរាល់ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'មានបញ្ហាក្នុងពេលកែប្រែថតឯកសារ។' ],
                201
            );
        }
    }
    /**
     * Active function of the account
     */
    public function active(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->active = $request->active ;
            $record->save();
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
    /**
     * Unactive function of the account
     */
    public function unactive(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->active = 0 ;
            $record->save();
            // Urecordser does exists
            return response([
                'ok' => true ,
                'record' => $record ,
                'message' => 'ជោគជ័យ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'សូមទោសមិនមានទេ !' ],
                201
            );
        }
    }
    // delete the folder 
    public function delete(Request $request){
        if( $request->id != "" 
         // && Auth::user() != null 
        ){
            $folder = \App\Models\Folder\Folder::find($request->id);
            if( $folder != null ){
                $record = $folder ;
                // Check for the regulators within the folder
                // If there is/are regulators within the folder then notify user first
                // process delete , also delete the related document within this folder [Note: we only delete the relationship of folder and document]
                if( $folder->regulators !== null && $folder->regulators->count() ){
                    foreach( $folder -> regulators as $documentFolder ){
                        $documentFolder -> delete ();
                    }
                }
                $folder->delete();
                return response([
                    'ok' => true ,
                    'record' => $record ,
                    'message' => "កម្រងឯកសារ " . $record->name . " បានលុបរួចរាល់ !" 
                    ],
                    200
                );
            }else{
                return response([
                    'ok' => false ,
                    'record' => $folder ,
                    'message' => "កម្រងឯកសារនេះមិនមានឡើយ !"
                    ],
                    201
                );
            }
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'សូមបញ្ជាក់កម្រងឯកសារដែលអ្នកចង់លុប !' ],
                201
            );
        }
    }
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
                
                if( !Storage::disk('public')->exists( $moduleFolder )){
                    if( !Storage::disk('public')->makeDirectory( $moduleFolder ) ){
                        return response()->json([
                            'ok' => false ,
                            'message' => "មិនអាចបង្កើតថតឯកសារបាន។"
                        ],500);
                    }
                }

                $userFolder = $moduleFolder.'/'.$user->id ;
                $uniqeName = Storage::disk('public')->putFile( $userFolder , new File( $_FILES['files']['tmp_name'] ) );
                $record->image = $uniqeName ;
                $record->save();
                if( $record->image != null && strlen( $record->image ) > 0 && Storage::disk('public')->exists( $record->image ) ){
                    $record->image = Storage::disk("public")->url( $record->image  );
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
    // Add document from folder
    public function addRegulatorToFolder(Request $request){
        if( $request->id > 0 && $request->regulator_id > 0 
          // && Auth::user() != null 
        ){
            $documentFolder = \App\Models\Regulator\RegulatorFolder::where('folder_id', $request->id )
                ->where('regulator_id' , $request->regulator_id )->first();
            if( $documentFolder == null ){
                $documentFolder = new \App\Models\Regulator\RegulatorFolder();
                $documentFolder -> folder_id = $request->id ;
                $documentFolder -> regulator_id = $request->regulator_id ;
                // $documentFolder -> created_by = $documentFolder -> updated_by = \Auth::user()->id ;
                $documentFolder->save();
                return response([
                    'ok' => true ,
                    'record' => $documentFolder ,
                    'message' => "បានបញ្ចូលឯកសារ ចូលទៅក្នុងកម្រងឯកសារ រួចរាល់ !"
                    ],
                    200
                );
            }else{
                return response([
                    'ok' => true ,
                    'record' => $documentFolder ,
                    'message' => "ឯកសារនេះមានក្នុងកម្រងឯកសារនេះរួចរាល់ហើយ !"
                    ],
                    201
                );
            }
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'record' => null ,
                'message' => 'សូមបំពេញព័ត៌មាន អោយបានគ្រប់គ្រាន់ !' ],
                201
            );
        }
    }
    // Remove document from folder
    public function removeRegulatorFromFolder(Request $request){
        if( $request->id > 0 && $request->regulator_id > 0 
        // && Auth::user() != null 
        ){
            $documentFolder = \App\Models\Regulator\RegulatorFolder::where('folder_id', $request->id )
                ->where('regulator_id' , $request->regulator_id )->first();
            $message = $documentFolder !== null ? "បានដកឯកសារចេញរួចរាល់។" : "មិនមានឯកសារនេះក្នុងថតឯកសារឡើយ។" ;
            if( $documentFolder != null ) {
                $documentFolder->delete();
            }
            return response([
                'ok' => true ,
                'record' => $documentFolder ,
                'message' => $message
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'record' => null ,
                'message' => 'សូមបំពេញព័ត៌មាន អោយបានគ្រប់គ្រាន់ !' ],
                201
            );
        }
    }
    public function checkRegulator(Request $request){
        $folder = RecordModel::find( $request->id );
        if( $folder !== null ){
            if( count( $folder -> regulators ) ){
                // There is/are document(s) within this folder
                return response([
                    'ok' => true ,
                    'record' => $folder ,
                    'message' => 'កម្រងឯកសារនេះ មានឯកសារចំនួន '. count( $folder -> regulators ) .' !' ],
                    200
                );
            }else{
                // There is no document within this folder
                return response([
                    'ok' => true ,
                    'record' => $folder ,
                    'message' => 'កម្រងឯកសារនេះ មិនមានឯកសារឡើយ !' ],
                    201
                );
            }
        }else{
            return response([
                'ok' => false ,
                'record' => null ,
                'message' => 'កម្រងឯកសារនេះ មិនមានឡើយ !' ],
                201
            );
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់ថតឯកសារ។'
            ],201);
        }
        $regulator = RecordModel::find($request->id);
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ថតឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $regulator->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'record' => $regulator ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ថតឯកសារ។'
        ],201);
    }
    /**
     * Listing regulators of the folder
     */
    public function regulators(Request $request){
        $user = Auth::user();

        /**
         * Geting all the regulators of the folder
         */
        if( !isset( $request->folder_id ) || $request->folder_id <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់របស់ថតឯកសារ។'
            ],350);
        }

        $regulatorIds = RecordModel::find($request->folder_id)->regulators->pluck('id')->all();
        if( count( $regulatorIds ) <= 0 ) {
            return response()->json([
                'ok' => false ,
                'message' => 'ថតឯកសារនេះមិនមានឯកសារឡើយ។'
            ],350);
        }
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                // // 'default' => [
                //     [
                //         'field' => 'user_id' ,
                //         'value' => $user->id
                //     ]
                // ],
                // 'in' => [
                //     [
                //         'field' => 'document_type' ,
                //         'value' => isset( $request->document_type ) && $request->document_type !== null ? [$request->document_type] : false
                //     ]
                // ] ,
                    'in' => [
                        [
                            'field' => 'id' ,
                            'value' => $regulatorIds
                        ]
                    ]
                // 'not' => [
                //     // [
                //     //     'field' => 'field_name' ,
                //     //     'value' => [4]
                //     // ]
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
            // "pivots" => [
            //     $unit ?
            //     [
            //         "relationship" => 'units',
            //         "where" => [
            //             "in" => [
            //                 "field" => "id",
            //                 "value" => [$request->unit]
            //             ],
            //         // "not"=> [
            //         //     [
            //         //         "field" => 'fieldName' ,
            //         //         "value"=> 'value'
            //         //     ]
            //         // ],
            //         // "like"=>  [
            //         //     [
            //         //        "field"=> 'fieldName' ,
            //         //        "value"=> 'value'
            //         //     ]
            //         // ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'objective'
                ]
            ],
            "order" => [
                'field' => 'objective' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new \App\Models\Regulator\Regulator(), $request, [
            'id',
            'fid' ,
            'title' ,
            'objective',
            'year' ,
            'pdf' ,
            'publish'
        ]);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'regulators' => [ 'objective' , 'fid' ] ,
            'user' => [ 'id' , 'lastname', 'firstname' ] ,
            'types' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'ministries' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder,
            [
                'field' => 'pdf' ,
                'callback'=> function($pdf){
                    $pdf = ( $pdf !== "" && \Storage::disk('public')->exists( $pdf ) )
                    ? \Storage::disk('public')->url( $pdf ) : null ;
                    return $pdf ;
                }
            ]
        );
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        // $responseData['folderIds'] = $folderIds ;
        // $responseData['sql'] = $builder->toSql();
        return response()->json($responseData, 200);
    }
    public function accessibility(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ថតឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ថតឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $result = in_array( intVal( $request->mode ) , [ 0 , 1 , 2 , 4 ] ) != false ? $record->update(['accessibility'=> intVal( $request->mode ) ] ) : false ;
        return response()->json([
            'record' => $result == false ? null : $record ,
            'ok' =>  $result == false ? false : true ,
            'message' => $result == false ? "មានបញ្ហាក្នុងការកែប្រែ។" : 'បានកែរួចរាល់។'
        ], $result == false ? 422 : 200 );
    }
}
