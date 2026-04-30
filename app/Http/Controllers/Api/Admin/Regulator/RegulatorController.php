<?php

namespace App\Http\Controllers\Api\Admin\Regulator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Regulator\Regulator as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;

class RegulatorController extends Controller
{
    private $selectFields = [
        'id',
        'fid' ,
        'title' ,
        'objective',
        'year' ,
        'pdf' ,
        'publish' ,
        'active' ,
        'document_type' ,
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

        $organizations = isset( $request->organizations ) ? array_filter( explode(',',$request->organizations) , function($organization){ return intval( $organization );} ) : false ;
        $signatures = isset( $request->signatures ) ? array_filter( explode(',',$request->signatures) , function($signature){ return intval( $signature ) ;}) : false ;
        $types = isset( $request->types ) && strlen( $request->types ) > 0 
            ? (
                array_filter( explode(',',$request->types) , function($type){ return intval( $type ) ;})
            )
            : false ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'created_by' ,
                //         'value' => $user->id
                //     ]
                // ],
                'in' => [
                    $user->roles()->pluck('name')->filter( function( $val , $key ) {
                        return $val == 'backend' ;
                    } )->count()
                        ?[ 
                            'field' => 'created_by' ,
                            'value' => [$user->id] 
                        ]
                        : []
                    // ,
                    // [
                    //     'field' => 'active' ,
                    //     'value' => 1
                    // ] ,
                    // [
                    //     'field' => 'publish' ,
                    //     'value' => 1
                    // ] ,
                    // [
                    //     'field' => 'accessibility' ,
                    //     'value' => [ 1,2,3,4 ]
                    // ]
                ] ,
                // 'not' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
                //     ]
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'fid' ,
                //         'value' => $fid === false ? "" : $fid
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            "pivots" => [
                $types ?
                [
                    "relationship" => 'type',
                    "where" => [
                        "in" => [
                            "field" => "document_type",
                            "value" => $types
                        ],
                    ]
                ]
                : [] ,
                $signatures ?
                [
                    "relationship" => 'signatures',
                    "where" => [
                        "in" => [
                            "field" => "signature_id",
                            "value" => $signatures
                        ],
                    ]
                ]
                : [] ,
                $organizations ?
                [
                    "relationship" => 'organizations',
                    "where" => [
                        "in" => [
                            "field" => "organization_id",
                            "value" => $organizations
                        ],
                    ]
                ]
                : []
            ],
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
                $record->pdf = ( strlen( $record->pdf ) > 0 && \Storage::disk('regulator')->exists( str_replace( [ 'regulators/' , 'documents/' ] , '' , $record->pdf ) ) )
                ? true
                // \Storage::disk('regulator')->url( $pdf ) 
                : false ;
                return $record->pdf ;
            },
            'objective' => function($record){
                return html_entity_decode( strip_tags( $record->objective ) );
            } 
        ]);

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'organizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            // 'books' => [ 'id' , 'name' ] ,
            'type' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['server'] = $_SERVER ;
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
                //         'field' => 'fid' ,
                //         'value' => $fid === false ? "" : $fid
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
                    $record->pdf = ( $record->pdf !== "" && \Storage::disk('regulator')->exists( $record->pdf ) )
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
     * Listing document by its type within a specific ministry
     */
    public function byTypeWithinOrganization($id){

        // Create Query Builder 
        $documentIds = \App\Models\Regulator\RegulatorOrganization::where('ministry_id',$id)->first()->getRegulators();
        $queryBuilder = new Regulator();

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'objective', 'LIKE' , "%".$term."%") ;
                    }
                } );
            }
        }
        // Get document type
        // if( $request->type != "" ){
        //     $documentTypes = explode(',', $request->type );
        //     if( is_array( $documentTypes ) && !empty( $documentTypes ) ){
        //         $queryBuilder = $queryBuilder -> where( function ($query ) use ( $documentTypes ) {
        //             foreach( $documentTypes as $type ) {
        //                 $query = $query -> orwhere ( 'type', $type ) ;
        //             }
        //         } );
        //     }
        // }
        // Get document year
        if( $request->year != "" ){
            $queryBuilder = $queryBuilder -> where('year','LIKE','%'.$request->year.'%');
        }
        // Get document registration id
        if( $request -> fid != "" ){
            $queryBuilder = $queryBuilder -> where('fid','LIKE','%'.$request -> fid);
        }

        $queryBuilder = $queryBuilder -> whereIn('id',$documentIds);
        // return $queryBuilder -> toSql();

        // $perpage = 
        return response([
            'records' => $queryBuilder->orderby('id','desc')->get()
                ->map( function ($record, $index) {
                    $record->objective = strip_tags( $record->objective ) ;
                    return $record ;
                })
            ,
            'message' => 'អានព័ត៌មាននៃគណនីបានរួចរាល់ !' 
        ],200 );
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $document = RecordModel::findOrFail($request->id);
        if($document) {
            // $record->pdf = ( $record->pdf !== "" && $record->pdf !== null && \Storage::disk('regulator')->exists( $record->pdf ) )
            // $path = storage_path('data') . '/regulators/' . $document->pdf;
            $pathPdf = storage_path('data') . '/regulators/' . str_replace([ 'regulators/' ,'documents/' ],'', $document->pdf ) ;
            $ext = pathinfo($pathPdf);
            $filename = str_replace('/' , '-', $document->fid) . "." . $ext['extension'];
        
            /**   Log the access of the user */
            $user = \Auth::user() != null ? \Auth::user() : auth('api')->user() ;
            if( $user != null ){
                \App\Models\Log\Log::regulator([
                    'system' => 'client' ,
                    'user_id' => $user->id ,
                    'regulator_id' => $document->id
                ]);
            }

            if(file_exists( $pathPdf ) && is_file($pathPdf)) {
                $pdfWatermark = storage_path('data') . '/watermarkfiles/' . str_replace([ 'regulators/' ,'documents/' ],'', $document->pdf );
                if( copy( 
                        $pathPdf , 
                        $pdfWatermark
                    )
                ){

                    // $watermarkPath = storage_path('data') . '/watermarkfiles/watermark5.png'  ;
                    // if( file_exists( $watermarkPath ) && is_file($watermarkPath) ){
                    //     // Check whether the pdf has once applied the watermark
                    //     if( !file_exists ( storage_path('data') . '/watermarkfiles/' . $document->pdf ) ){
                    //         // Specify path to the existing pdf
                    //         $pdf = new Pdf( $pathPdf );

                    //         // Specify path to image. The image must have a 96 DPI resolution.
                    //         $watermark = new ImageWatermark( $watermarkPath );
                            
                    //         // Create a new watermarker
                    //         $watermarker = new PDFWatermarker(
                    //             $pdf, 
                    //             $watermark
                    //         ); 

                    //         // Set the position of the watermark including optional X/Y offsets
                    //         // $position = new Position(Position::BOTTOM_CENTER, -50, -10);

                    //         // All possible positions can be found in Position::options
                    //         // $watermarker->setPosition($position);

                    //         // Place watermark behind original PDF content. Default behavior places it over the content.
                    //         // $watermarker->setAsBackground();


                    //         // Only Watermark specific range of pages
                    //         // This would only watermark page 3 and 4
                    //         // $watermarker->setPageRange(3, 4);
                            
                    //         // Save the new PDF to its specified location
                    //         $watermarker->save( storage_path('data') . '/watermarkfiles/' . $document->pdf );
                    //     }   

                    //     $pdfBase64 = base64_encode( 
                    //         file_get_contents( 
                    //             // $pathPdf 
                    //             storage_path('data') . '/watermarkfiles/' . $document->pdf
                    //         ) 
                    //     );
                        
                    //     return response([
                    //         'serial' => str_replace([ 'regulators/' ,'documents/' ],'', $document->pdf ) ,
                    //         "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    //         "filename" => $filename,
                    //         "ok" => true 
                    //     ],200);
                    // }else{
                    //     return response()->json([
                    //         'message' => 'មិនមានរូបផាព Watermark។' ,
                    //         'watermark' => $watermarkPath
                    //     ],200);
                    // }

                    $pdfBase64 = base64_encode( file_get_contents( $pdfWatermark ) );
                    
                    return response([
                        'serial' => str_replace([ 'regulators/' ,'documents/' ],'', $document->pdf ) ,
                        "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                        "filename" => $filename,
                        "ok" => true 
                    ],200);
                }else{
                    return response()->json([
                        'message' => 'មិនបញ្ហាអានអានឯកសារយោង'
                    ],403);
                }

            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $pathPdf
                ],500 );
            }
        }
    }
    public function upload(Request $request){
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
            if( isset( $_FILES['file'] ) && $_FILES['file']['error'] > 0 ){
                return response()->json([
                    'ok' => false ,
                    'message' => $phpFileUploadErrors[ $_FILES['file']['error'] ]
                ],403);
            }
            $kbFilesize = round( filesize( $_FILES['file']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $document = \App\Models\Regulator\Regulator::find($request->id) ) !== null ){
                list($year,$month,$day) = explode('-',$document->year);
                $uniqeName = Storage::disk('regulator')->putFile( '' , new File( $_FILES['file']['tmp_name'] ) );
                $document->pdf = $uniqeName ;
                $document->save();
                if( Storage::disk('regulator')->exists( $document->pdf ) ){
                    $document->pdf = Storage::disk("regulator")->url( $document->pdf  );
                    return response([
                        'record' => $document ,
                        'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
                    ],200);
                }else{
                    return response([
                        'record' => $document ,
                        'message' => 'មិនមានឯកសារយោងដែលស្វែងរកឡើយ។'
                    ],403);
                }
            }else{
                return response([
                    'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់ឯកសារយោង។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    /** Get the year(s) that there is/are documents published base on ministry_id and type_id */
    public function getRegulatorsAsOrganizationTypeYearMonth(Request $request){
        $ministries = \App\Models\Organization()->selectRaw('id, name')->orderby('name','asc')->get();
        // $tree = []
        // foreach( $ministries as $ministryIndex => $ministry ){
            
        //     foreach( $ministry->documents as $documentIndex => $document ){

        //     }
        // }    
    }
    public function create(Request $request){
        /**
         * Save information of the regulator and its related information
         */
        
        $record = RecordModel::create([
            'fid' => $request->fid?? ''  ,
            'title' => $request->title?? '' ,
            'objective' => $request->objective ,
            'year' => $request->year?? \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'publish' => 1 , // $request->publish
            'active' => $request->active > 0 ? 1 : 0 ,
            'created_by' => \Auth::user()->id ,
            'updated_by' => \Auth::user()->id ,
            'document_type' => $request->types
        ]);
        /**
         * Create detail information of the owner of the account
         */
        $record->created_by = \Auth::user()->id ;
        // $person = \App\Models\People\People::create([
        //     'firstname' => $record->firstname , 
        //     'lastname' => $record->lastname , 
        //     'mobile_phone' => $record->phone , 
        //     'email' => $record->email
        // ]);
        // $record->people_id = $person->id ;

        /**
         * Sync the organizations
         */
        if( count( $request->organizations ) > 0 ){
            $record->organizations()->sync( $request->organizations );
        }
        // if( count( $request->ownOrganizations ) > 0 ){
        //     $record->ownOrganizations()->sync( $request->ownOrganizations );
        // }
        // if( count( $request->relatedOrganizations ) > 0 ){
        //     $record->relatedOrganizations()->sync( $request->relatedOrganizations );
        // }
        /**
         * Sync the signatures
         */
        if( count( $request->signatures ) > 0 ){
            $record->signatures()->sync( $request->signatures );
        }
        /**
         * Sync the types
         */
        // if( isset( $request->types ) ){
        //     is_array( $request->types ) && count( $request->types ) > 0 
        //         ? $record->type()->sync( $request->types )
        //         :  $record->type()->sync( [$request->types] ) ;
        // }
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        if( isset( $request->id ) && $request->id > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            /**
             * Save information of the regulator and its related information
             */
            if( $record->update([
                'fid' => $request->fid ,
                'title' => $request->title ,
                'objective' => $request->objective ,
                'year' => $request->year ,
                // 'type' => $request->type_id ,
                'active' => $request->active > 0 ? 1 : 0 ,
                'updated_by' => \Auth::user()->id ,
                'document_type' => $request->types
            ]) ){

                /**
                 * Sync the organizations
                 */
                if( count( $request->organizations ) > 0 ){
                    $record->organizations()->sync( $request->organizations );
                }
                // if( count( $request->ownOrganizations ) > 0 ){
                //     $record->ownOrganizations()->sync( $request->ownOrganizations );
                // }
                // if( count( $request->relatedOrganizations ) > 0 ){
                //     $record->relatedOrganizations()->sync( $request->relatedOrganizations );
                // }
                /**
                 * Sync the signatures
                 */
                if( count( $request->signatures ) > 0 ){
                    $record->signatures()->sync( $request->signatures );
                }
                /**
                 * Sync the types
                 */
                // if( isset( $request->types) ){
                //     is_array( $request->types ) && count( $request->types ) > 0 
                //         ? $record->type()->sync( $request->types )
                //         :  $record->type()->sync( [$request->types] ) ;
                // }

                $responseData['message'] = __("crud.read.success");
                $responseData['ok'] = true ;
                $responseData['record'] = $record ;
                return response()->json($responseData, 200);
            }else{
                return response()->json([
                    'message' => 'មានបញ្ហាក្នុងការរក្សារព័ត៌មានឯកសារ។'
                ], 403);    
            }
        }else{
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ឯកសារ។'
            ], 403);
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],201);
        }
        $regulator = RecordModel::find($request->id);
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $regulator->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'record' => $regulator ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
        ],200);
    }

    public function destroy(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],201);
        }
        $regulator = RecordModel::find($request->id);
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារស្វែករកបានជោគជ័យ។'
            ],201);
        }
        $regulator->with('ministries')->with('signatures')->with('ministries')->with('type');
        $tempRecord = $regulator;
        if( $regulator->delete() ){
            /**
             * Delete all the related documents own by this regulator
             */
            if( $tempRecord->pdf !== null && $tempRecord->pdf !=="" && Storage::disk('regulator')->exists( $tempRecord->pdf ) ){
                Storage::disk("document")->delete( $tempRecord->pdf  );
            }
            return response()->json([
                'record' => $tempRecord ,
                'ok' => true ,
                'message' => 'លុបទិន្នបានជោគជ័យ។'
            ],200);
        }
        return response()->json([
            'record' => $tempRecord ,
            'ok' => false ,
            'message' => 'មានបញ្ហាក្នុងការលុបទិន្ន័យ។'
        ],201);
    }
    public function oknha(Request $request){
        $records = \App\Models\Regulator\Regulator::select(['id','fid','objective','year'])->where('objective','LIKE','%ឧកញ៉ា%')
        // ->orWhere('fid','LIKE','%អ្នកឧកញ៉ា%')
        ->whereNot('objective',"LIKE",'%ឥស្សរិយយស%')
        ->whereNotIn('id',[51567, 20014, 19451, 45672, 45673, 45684, 45688, 45693, 45697, 45705, 45716, 45717, 51794, 51453,  45723, 45724, 50355, 45761, 45764, 58693, 56440, 58908, 58458, 57730, 57705, 57692, 57677, 57073, 56265, 56148, 56084, 55414, 54445, 53835, 52968, 52965, 52049, 52036, 52034, 51409, 51408, 51407, 50318, 49856, 49601, 49564, 48893, 46788, 46760 ])
        ->get()->map(function($r){
            $r->objective = trim( str_replace( [ 'ព្រះរាជក្រឹត្យស្ដីពីការតែងតាំង' ,'ព្រះរាជក្រឹត្យស្ដីពីការត្រាស់បង្គាប់តែងតាំង' , 'ព្រះរាជក្រឹត្យស្ដីពីតែងតាំង' , 'ព្រះរាជក្រឹត្យស្ដីពីការត្រាស់បង្គាប់' , 'ផ្ដល់គោរមងារ' , '&nbsp;' , 'ផ្ដល់គោរមងា' , 'ព្រះរាជក្រឹត្យស្ដីពីការត្រាសបង្គាប់', 'ព្រះរាជក្រឹត្យស្ដីពីកាផ្តល់គោរមងារ' , 'រះរាជក្រឹត្យស្ដីពីការត្រាស់បង្គាប់' , 'ផ្ដល់គោរពងារ' , 'ផ្តល់គោរម្យងារ' , 'គោរមងារ' ],[ '' ],strip_tags( $r->objective ) ) );
            return $r;
        });
        return view( 'oknha' , ['data' => $records] );
    }
    public function activate(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'ok' => $record->update(['active'=>1]) ,
            'record' => $record ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }
    public function deactivate(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'ok' => $record->update(['active'=>0]) ,
            'record' => $record ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }
    public function publish(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'ok' => $record->update(['publish'=>1]) ,
            'record' => $record ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }
    public function unpublish(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'ok' => $record->update(['publish'=>0]) ,
            'record' => $record ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }
    public function accessibility(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $result = in_array( intVal( $request->mode ) , [ 0 , 1 , 2 , 4 ] ) != false ? $record->update(['accessibility'=> intVal( $request->mode ) ] ) : false ;
        return response()->json([
            'record' => $result == false ? null : $record ,
            'ok' =>  $result == false ? false : true ,
            'message' => $result == false ? "មានបញ្ហាក្នុងការកែប្រែ។" : 'បានកែរួចរាល់។'
        ], $result == false ? 422 : 200 );
    }
    /**
     * Add reader(s) of the specific document
     */
    public function addReaders(Request $request){
        $regulator = \App\Models\Regulator\Regulator::find($request->document_id);
        if( $regulator != null ){
            return response()->json([
                /**
                 * It will return in the following format : [ attached => [] , detached => [] ]
                 */
                'result' => $regulator->readers()->toggle([$request->user_id])['attached'] ,
                'message' => 'បញ្ចូលអ្នកអានរួចរាល់។'
            ],200);
        }
        return response()->json([
            'message' => 'មានបញ្ហាក្នុងការបញ្ចូលអ្នកអានឯកសារនេះ។'
        ],422);
    }

    /**
     * Remove reader(s) of the specific document
     */
    public function removeReaders(Request $request){
        $regulator = \App\Models\Regulator\Regulator::find($request->document_id);
        if( $regulator != null ){
            return response()->json([
                'record' => $regulator->readers()->toggle([$request->user_id])['detached'] ,
                'message' => 'ដកអ្នកអានរួចរាល់។'
            ],200);
        }
        return response()->json([
            'message' => 'មានបញ្ហាក្នុងការដកអ្នកអានឯកសារនេះ។'
        ],422);
    }
}
