<?php

namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document\Document as RecordModel;
use App\Http\Controllers\CrudController;


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
        'publish'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

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
                //         'field' => 'document_type' ,
                //         'value' => isset( $request->document_type ) && $request->document_type !== null ? [$request->document_type] : false
                //     ]
                // ] ,
                'not' => [
                    [
                        'field' => 'document_type' ,
                        'value' => [4]
                    ]
                ] ,
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
        if( isset( $request->document_type ) ) {
            $queryString['where']['default'] = [
                'in' => [
                    [
                        'field' => 'document_type' ,
                        'value' =>  $request->document_type
                    ]
                ]
            ];
        }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            "type" => ['id', 'name', 'format', 'color', 'index'] ,
            "ministries" => ['id', 'name']
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
        return response()->json($responseData, 200);
    }
    /**
     * Listing document by its type within a specific ministry
     */
    public function byTypeWithinMinistry($id){

        // Create Query Builder 
        $documentIds = \App\Models\Document\DocumentMinistry::where('ministry_id',$id)->first()->getDocuments();
        $queryBuilder = new Document();

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
        if( $request->document_type != "" ){
            $documentTypes = explode(',', $request->document_type );
            if( is_array( $documentTypes ) && !empty( $documentTypes ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $documentTypes ) {
                    foreach( $documentTypes as $type ) {
                        $query = $query -> orwhere ( 'document_type', $type ) ;
                    }
                } );
            }
        }
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
        $document = Document::findOrFail($request->id);
        if($document) {
            $path = storage_path('data') . '/' . $document->pdf;
            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $document->fid) . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    /** Get the year(s) that there is/are documents published base on ministry_id and document_type_id */
    public function getDocumentsAsMinistryTypeYearMonth(Request $request){
        $ministries = \App\Models\Ministry()->selectRaw('id, name')->orderby('name','asc')->get();
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
        
        RecordModel::create([
            'fid' => $request->number ,
            'title' => $request->title ,
            'objective' => $request->objective ,
            'year' => $request->year ,
            'document_type' => $request->type_id ,
            'publish' => $request->publish
        /**
         * Upload pdf document(s) of the regulator
         */
        ]);
    }
    public function update(Request $request){

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
        ],201);
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
}
