<?php

namespace App\Http\Controllers\Api\Client\Regulator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Regulator\Regulator as RecordModel;
use App\Http\Controllers\CrudController;

use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;

class SearchController extends Controller
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
        'created_by' ,
        'accessibility'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        \App\Models\Log\Log::access([
            'system' => 'client' ,
            'user_id' => \Auth::user() != null 
                ? \Auth::user()->id
                : (
                    auth('api')->user() 
                        ? auth('api')->user()->id
                        : (
                            $request->user() != null
                                ? $request->user()->id 
                                : 0
                        )
                ),
            'class' => self::class ,
            'func' => __FUNCTION__ ,
            'desp' => 'search regulators'
        ]); 
        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && intval( $request->page ) > 0 ? $request->page : 1 ;
        
        $fid = isset( $request->fid ) && $request->fid != "" && $request->fid != null && $request->fid != 'null' ? $request->fid : false ;
        $year = isset( $request->year ) && $request->year != "" && $request->year != null && $request->year != 'null'  ? \Carbon\Carbon::parse( $request->year ) : false ;
        $types = isset( $request->types ) && $request->types != "" && $request->types != 'null' ? explode(',',$request->types) : false ;
        $signatures = isset( $request->signatures ) && $request->signatures != "" && $request->signatures != 'null' ? explode(',',$request->signatures) : false ;
        $organizations = isset( $request->organizations ) && $request->organizations != "" && $request->organizations != 'null' ? explode(',',$request->organizations) : false ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'active' ,
                        'value' => 1
                    ] ,
                    [
                        'field' => 'publish' ,
                        'value' => 1
                    ] ,
                    [
                        'field' => 'accessibility' ,
                        'value' => [ 4 ]
                    ]
                ],
                // 'in' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
                //     ]
                // ] ,
                // 'not' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
                //     ]
                // ] ,
                'like' => [
                    $year ? [
                        'field' => 'year' ,
                        'value' => $year->format('Y-m-d')
                    ] : [] 
                    ,
                    $fid ? [
                        'field' => 'fid' ,
                        'value' => $fid
                    ] : []
                ] ,
            ] ,
            "pivots" => [
                // $types ?
                // [
                //     "relationship" => 'types',
                //     "where" => [
                //         "in" => [
                //             "field" => "type_id",
                //             "value" => $types
                //         ]
                //     ]
                // ]
                // : [] ,
                $signatures ?
                [
                    "relationship" => 'signatures',
                    "where" => [
                        "in" => [
                            "field" => "signature_id",
                            "value" => $signatures
                        ]
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
                        ]
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
                    'objective'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            'pdf' => function($record){
                return ( strlen( $record->pdf ) > 0 && \Storage::disk('regulator')->exists( str_replace( [ 'regulators/' , 'documents/' ] , '' , $record->pdf ) ) )
                ? true
                // \Storage::disk('regulator')->url( $pdf ) 
                : false ;
            },
           'objective' => function($record){
                return html_entity_decode( strip_tags( $record->objective ) );
            }
        ], false, false , 'regulators');
        /**
         * Set the storage driver name for CRUD
         */
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'createdBy' => [ 'id' , 'firstname' , 'lastname' ] ,
            'type' => [ 'id' , 'name' ] ,
            'signatures' => [ 'id' , 'name' ] ,
            'organizations' => [ 'id' , 'name' ]
        ]);

        $builder = $crud->getListBuilder();
        
        if( is_array( $types ) && !empty( $types ) ){
            $builder->whereIn('document_type', $types );
        }

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Listing regulator by its type within a specific ministry
     */
    public function byTypeWithinOrganization($id){
        // Create Query Builder 
        $regulatorIds = \App\Models\Regulator\RegulatorOrganization::where('organization_id',$id)->first()->getRegulators();
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
        // Get regulator type
        // if( $request->type != "" ){
        //     $regulatorTypes = explode(',', $request->type );
        //     if( is_array( $regulatorTypes ) && !empty( $regulatorTypes ) ){
        //         $queryBuilder = $queryBuilder -> where( function ($query ) use ( $regulatorTypes ) {
        //             foreach( $regulatorTypes as $type ) {
        //                 $query = $query -> orwhere ( 'regulator_type', $type ) ;
        //             }
        //         } );
        //     }
        // }
        // Get regulator year
        if( $request->year != "" ){
            $queryBuilder = $queryBuilder -> where('year','LIKE','%'.$request->year.'%');
        }
        // Get regulator registration id
        if( $request -> fid != "" ){
            $queryBuilder = $queryBuilder -> where('fid','LIKE','%'.$request -> fid);
        }

        $queryBuilder = $queryBuilder -> whereIn('id',$regulatorIds);
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
        \App\Models\Log\Log::access([
            'system' => 'client' ,
            'user_id' => \Auth::user() != null 
                ? \Auth::user()->id
                : (
                    auth('api')->user() 
                        ? auth('api')->user()->id
                        : (
                            $request->user() != null
                                ? $request->user()->id 
                                : 0
                        )
                ),
            'class' => self::class ,
            'func' => __FUNCTION__ ,
            'desp' => 'read pdf file of regulator'
        ]); 
        $regulatorId = isset( $request->id ) && intval( $request->id ) > 0 ? intval($request->id) : false ;
        $regulatorSerial = isset( $request->serial ) && is_string( $request->serial ) && strlen( $request->serial ) > 0 ? $request->serial : false ;
        $regulator = $regulatorId 
            ? RecordModel::findOrFail($request->id) 
            : (
                $regulatorSerial 
                ? RecordModel::where('pdf','like','%' . $regulatorSerial . '.pdf%')->first()
                : false
            ) ;
        if( $regulator ) {
            
            /**   Log the access of the user */
            $user = \Auth::user() != null ? \Auth::user() : auth('api')->user() ;
            if( $user != null ){
                \App\Models\Log\Log::regulator([
                    'system' => 'client' ,
                    'user_id' => $user->id ,
                    'regulator_id' => $regulator->id
                ]);
            }

            /**
             * Check whether the regulator a public (accessibility = 2 ) or global type (accessibility = 4)
             */
            if( !in_array( $regulator->accessibility , [ 2, 4 ] ) ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានសិទ្ធិលើឯកសារ។'
                ],403);
            }
            /**
             * Check whether the pdf is array or string of regulator path
             */
            $path = '' ;
            if( 
                is_array( $regulator->pdf ) &&
                !empty( $regulator->pdf )
            ){
                foreach( $regulator->pdf AS $index => $pdfPath ){
                    if( 
                        ( strlen( $pdfPath ) > 0 && 
                        \Storage::disk('regulator')->exists( str_replace( [ 'regulators/' , 'documents/' ] , '' , $pdfPath ) ) )
                    ){
                        $path = storage_path('data') . '/regulators/' . str_replace( [ 'regulators/' , 'documents/' ] , '' , $pdfPath ) ;
                        break ;
                    }
                }
            }
            if( 
                is_string( $regulator->pdf ) && 
                strlen( $regulator->pdf ) > 0 && 
                \Storage::disk('regulator')->exists( str_replace( [ 'regulators/' , 'documents/' ] , '' , $regulator->pdf ) ) 
            ){
                $path = storage_path('data') . '/regulators/' . str_replace( [ 'regulators/' , 'documents/' ] , '' , $regulator->pdf ) ;
            }

            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $regulator->fid) . "." . 'pdf' ;

            // if(is_file($path)) {
            if(file_exists($path)) {

                // Check whether the pdf has once applied the watermark
                // if( !file_exists (storage_path('data') . '/watermarkfiles/' . $regulator->pdf ) ){
                //     // Specify path to the existing pdf
                //     $pdf = new Pdf( $path );

                //     // Specify path to image. The image must have a 96 DPI resolution.
                //     $watermark = new ImageWatermark( 
                //         storage_path('data') . 
                //         '/watermark5.png' 
                //     );

                //     // Create a new watermarker
                //     $watermarker = new PDFWatermarker($pdf, $watermark); 

                //     // Set the position of the watermark including optional X/Y offsets
                //     // $position = new Position(Position::BOTTOM_CENTER, -50, -10);

                //     // All possible positions can be found in Position::options
                //     // $watermarker->setPosition($position);

                //     // Place watermark behind original PDF content. Default behavior places it over the content.
                //     // $watermarker->setAsBackground();


                //     // Only Watermark specific range of pages
                //     // This would only watermark page 3 and 4
                //     // $watermarker->setPageRange(3, 4);
                    
                //     // Save the new PDF to its specified location
                //     $watermarker->save( storage_path('data') . '/watermarkfiles/' . $regulator->pdf );
                // }   
                $pdfWatermark = storage_path('data') . '/watermarkfiles/' . str_replace([ 'regulators/' ,'documents/' ],'', $regulator->pdf );
                if( 
                    copy( 
                        $path , 
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
                        'serial' => str_replace([ 'regulators/' ,'documents/' ],'', $regulator->pdf ) ,
                        "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                        "filename" => $filename,
                        "ok" => true
                    ],200);
                }else{
                    return response()->json([
                        'message' => 'មិនបញ្ហាអានអានឯកសារយោង'
                    ],403);
                }

                // $pdfBase64 = base64_encode( 
                //     file_get_contents( 
                //         // $pathPdf 
                //         storage_path('data') . '/watermarkfiles/' . $regulator->pdf
                //     ) 
                // );

                // // $pdfBase64 = base64_encode( file_get_contents($path) );
                // return response([
                //     'serial' => $regulatorSerial ,
                //     "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                //     "filename" => $filename ,
                //     "ok" => true
                // ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path ,
                    'regulator' => $regulator
                ],404 );
            }
        }else{
            return response([
                'message' => 'មិនមានឯកសារដែលស្វែងរក។' ,
                'ok' => false
            ],403 );
        }
    }
    /**
     * View the pdf file
     */
    public function sharedPdf(Request $request)
    {
        \App\Models\Log\Log::access([
            'system' => 'client' ,
            'user_id' => \Auth::user() != null 
                ? \Auth::user()->id
                : (
                    auth('api')->user() 
                        ? auth('api')->user()->id
                        : (
                            $request->user() != null
                                ? $request->user()->id 
                                : 0
                        )
                ),
            'class' => self::class ,
            'func' => __FUNCTION__ ,
            'desp' => 'share regulator\'s pdf file'
        ]); 
        $regulatorSerial = isset( $request->serial ) && is_string( $request->serial ) ? $request->serial : false ;
        $regulator = $regulatorSerial 
            ? RecordModel::where('pdf','like','%' . $regulatorSerial . '.pdf%')->first()
            : false;
        if( $regulator ) {
            /**
             * Check whether the regulator a public (accessibility = 2 ) or global type (accessibility = 4)
             */
            if( $regulator->accessibility != 4 ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'ឯកសារមិនមានឡើយ។'
                ],403);
            }
            /**
             * Check whether the pdf is array or string of regulator path
             */
            $path = '' ;
            if( $regulatorSerial !== false ){
                $path = storage_path('data') . '/documents/' . $regulatorSerial . '.pdf' ;    
            }

            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $regulator->fid) . "." . $ext['extension'];
            
            /**   Log the access of the user */
            // if( \Auth::user() !== null ){
            //     $current_date = date('Y-m-d H:i:s');
            //     DB::insert('insert into regulator_view_logs (user_id, regulator_id, date) values (?,?,?)', [\Auth::user()->id, $regulator->id, $current_date]);
            // }

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename ,
                    "ok" => true
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }else{
            return response([
                'message' => 'មិនមានឯកសារដែលស្វែងរក។' ,
                'ok' => false
            ],403 );
        }
    }
    /** Get the year(s) that there is/are regulators published base on ministry_id and regulator_type_id */
    public function getRegulatorsAsOrganizationTypeYearMonth(Request $request){
        $ministries = \App\Models\Organization()->selectRaw('id, name')->orderby('name','asc')->get();
        // $tree = []
        // foreach( $ministries as $ministryIndex => $ministry ){
            
        //     foreach( $ministry->regulators as $regulatorIndex => $regulator ){

        //     }
        // }    
    }

}
