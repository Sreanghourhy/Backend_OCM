<?php

namespace App\Http\Controllers\Api\Hradmin\Regulator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Regulator\Regulator as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class RegulatorController extends Controller
{
    private $selectFields = [
        'id',
        'fid' ,
        'title' ,
        'objective',
        'year' ,
        'pdf' ,
        'document_type' ,
        'publish' ,
        'active' ,
        'created_by' ,
        'updated_by' ,
        'accessibility'
    ];
        
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'created_by' ,
                //         'value' => $user->id
                //     ]
                // ],

                // 'in' => [
                //     $user->id > 0 
                //         ? [
                //             'field' => 'created_by' ,
                //             'value' => [ $user->id ]
                //         ]
                //         : []
                // ] ,
                // 'not' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
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
                    'objective', 'fid', 'year'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            /**
             * custom the value of the field
             */
            'pdf' => function($record){
                return strlen( $record->pdf ) > 0 ? ( \Storage::disk('regulator')->exists( $record->pdf ) || \Storage::disk('document')->exists( $record->pdf ) ) : false ;
            },
            'serial' => function($record){
                return \Storage::disk('regulator')->url( $record->pdf );
            },
           'objective' => function($record){
                    return html_entity_decode( strip_tags( $record->objective ) );
                }
            ]
        );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'organizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'ownOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'relatedOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'types' => [ 'id' , 'name' , 'desp' , 'pid' ] 
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Listing function
     */
    public function child(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        /**
         * Get the id of the regulator and its parents to exclude them from searching
         */
        $regulator = isset( $request->parent_id ) && $request->parent_id > 0 ? \App\Models\Regulator\Regulator::find( $request->parent_id ) : false ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'publish' ,
                //         'value' => 0
                //     ]
                // ],
                // 'in' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
                //     ]
                // ] ,
                'not' => [
                    [
                        'field' => 'id' ,
                        'value' => [ $request->parent_id ]
                    ]
                ] // ,
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
                    'objective', 'fid', 'year'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'organizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'ownOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'relatedOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'types' => [ 'id' , 'name' , 'desp' , 'pid' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder,[
                'pdf' => function($record){
                    $record->pdf = ( $record->pdf !== "" && ( \Storage::disk('regulator')->exists( $record->pdf ) || \Storage::disk('document')->exists( $record->pdf ) ) )
                    ? true
                    // \Storage::disk('regulator')->url( $pdf ) 
                    : false ;
                    return $record->pdf ;
                },
                'objective' => function($record){
                    return html_entity_decode( strip_tags( $record->objective ) );
                }
            ]
        );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $regulator = RecordModel::findOrFail($request->id);
        if($regulator) {
            $path = storage_path('data') . '/regulators/' . $regulator->pdf;
            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $regulator->fid) . "." . $ext['extension'];

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    'serial' => str_replace(['regulators','documents','/','.pdf'],'',$regulator->pdf ) ,
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'regulator' => $regulator ,
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
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
}
