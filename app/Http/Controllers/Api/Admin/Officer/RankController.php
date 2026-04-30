<?php

namespace App\Http\Controllers\Api\Admin\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Officer\Rank as RecordModel;
use App\Models\Officer\Officer ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;

class RankController extends Controller
{
    private $selectFields = [
        'id' ,
        'ank' , 
        'krobkhan' , 
        'rank' , 
        'name' , 
        'desp' , 
        'tpid' , 
        'pid' , 
        'cids' , 
        'image' , 
        'record_index' , 
        'active' , 
        'prefix' , 
        'created_at' , 
        'updated_at' ,
        'created_by' , 
        'updated_by'
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
                //     $officer == null ? [] 
                //     : [
                //         'field' => 'officer_id' ,
                //         'value' => $officer->id
                //     ]
                // ],
                // 'in' => [
                //     $user->roles()->pluck('name')->filter( function( $val , $key ) {
                //         return $val == 'backend' ;
                //     } )->count()
                //         ?[ 
                //             'field' => 'created_by' ,
                //             'value' => [$user->id] 
                //         ]
                //         : []
                //     // ,
                //     // [
                //     //     'field' => 'active' ,
                //     //     'value' => 1
                //     // ] ,
                //     // [
                //     //     'field' => 'publish' ,
                //     //     'value' => 1
                //     // ] ,
                //     // [
                //     //     'field' => 'accessibility' ,
                //     //     'value' => [ 1,2,3,4 ]
                //     // ]
                // ] ,
                // 'not' => [
                //     count( $weddingCertificateIds )
                //         ?[ 
                //             'field' => 'wedding_certificate_id' ,
                //             'value' => $weddingCertificateIds
                //         ]
                //         : []
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
            // "pivots" => [
            //     $types ?
            //     [
            //         "relationship" => 'type',
            //         "where" => [
            //             "in" => [
            //                 "field" => "document_type",
            //                 "value" => $types
            //             ],
            //         ]
            //     ]
            //     : [] ,
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'ank' , 
                    'krobkhan' , 
                    'rank' , 
                    'name' , 
                    'desp' , 
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            /**
             * custom the value of the field
             */
            'pdf' => function($record){
                $record->pdf = ( strlen( $record->pdf ) > 0 && \Storage::disk('certificate')->exists( $record->pdf ) )
                ? true
                // \Storage::disk('regulator')->url( $pdf ) 
                : false ;
                return $record->pdf ;
            }
        ]);

        // return response()->json([ 
        //     'time' => microtime()
        // ]);

        // $crud->setRelationshipFunctions([
        //     /** relationship name => [ array of fields name to be selected ] */
        //     'officers' => [
        //         'code',
        //         'official_date' ,
        //         'unofficial_date',
        //         'public_key',
        //         'user_id' ,
        //         'people_id' ,
        //         'email',
        //         'phone',
        //         'countesy_id' ,
        //         // Optional
        //         'organization_id' ,
        //         'position_id' ,
        //         'rank_id' ,
        //         'leader' ,
        //         'image' ,
        //         'pdf',
        //         'passport' ,
        //         'people' => [ 'id' , 'firstname' ,'lastname' , 'enfirstname' , 'enlastname' ]
        //     ]
        // ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        // $responseData['server'] = $_SERVER ;
        return response()->json($responseData, 200);
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $certificate = RecordModel::findOrFail($request->id);
        if($certificate) {
            $pathPdf = storage_path('data') . '/certificates/' . $certificate->pdf ;
            $ext = pathinfo($pathPdf);
            $filename = $certificate->id . '-' .$certificate->field_name . "." . $ext['extension'];
        
            /**   Log the access of the user */
            // $user = \Auth::user() != null ? \Auth::user() : auth('api')->user() ;
            // if( $user != null ){
            //     \App\Models\Log\Log::regulator([
            //         'system' => 'client' ,
            //         'user_id' => $user->id ,
            //         'regulator_id' => $document->id
            //     ]);
            // }

            if(file_exists( $pathPdf ) && is_file($pathPdf)) {
                $pdfBase64 = base64_encode( file_get_contents( $pathPdf ) );
                return response([
                    'serial' => $certificate->pdf ,
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារ !' ,
                    'path' => $pathPdf
                ],500 );
            }
        }
    }
}