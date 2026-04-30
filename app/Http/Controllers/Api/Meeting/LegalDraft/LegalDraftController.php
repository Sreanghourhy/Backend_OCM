<?php

namespace App\Http\Controllers\Api\Meeting\LegalDraft;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Regulator\LegalDraft as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class LegalDraftController extends Controller
{
    private $selectFields = [
        'id',
        'regulator_id',
        'title' ,
        'objective',
        'content' ,
        'files' ,
        'created_by' ,
        'updated_by' ,
        'deleted_by' ,
        'created_at' ,
        'updated_at'
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
                    'objective', 'content'
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

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,
            [
                'objective' => function($record){
                    return html_entity_decode( strip_tags( $record->objective ) );
                } ,
                'content' => function($record){
                    return html_entity_decode( strip_tags( $record->content ) );
                }
            ]
        );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'regulator' => [ 
                'id' , 'title' , 'objective' , 'fid' , 'year' 
                , 'type' => [ 'id' , 'name' , 'desp' ]
            ] ,
            'meetings' => [ 
                'id' , 'objective' , 'start' , 'end' , 'actual_start' , 'actual_end' , 'status'
                , 'type' => [ 'id' , 'name' , 'desp' ]
            ] ,
            'creator' => [ 'id' , 'firstname' , 'lastname' , 'phone' , 'email' ] ,
            'editor' => [ 'id' , 'firstname' , 'lastname' , 'phone' , 'email' ] ,
            'createdBy' => [ 'id' , 'firstname' , 'lastname' , 'phone' , 'email' ] 
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * View the pdf file
     */
    // public function pdf(Request $request)
    // {
    //     $document = RecordModel::findOrFail($request->id);
    //     if($document) {
    //         // $record->pdf = ( $record->pdf !== "" && $record->pdf !== null && \Storage::disk('regulator')->exists( $record->pdf ) )
    //         $path = storage_path('data') . '/regulators/' . $document->pdf;
    //         $ext = pathinfo($path);
    //         $filename = str_replace('/' , '-', $document->fid) . "." . $ext['extension'];
            
    //         /**   Log the access of the user */
    //         //   $user_id= Auth::user()->id;
    //         //   $current_date = date('Y-m-d H:i:s');
    //         //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

    //         if(is_file($path)) {
    //             $pdfBase64 = base64_encode( file_get_contents($path) );
    //             return response([
    //                 'serial' => str_replace(['regulators','/','.pdf'],'',$document->pdf ) ,
    //                 "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
    //                 "filename" => $filename,
    //                 "ok" => true 
    //             ],200);
    //         }else
    //         {
    //             return response([
    //                 'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
    //                 'path' => $path
    //             ],500 );
    //         }
    //     }
    // }
    // public function upload(Request $request){
    //     $user = \Auth::user();
    //     if( $user ){
    //         $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
    //         $mbFilesize = round( $kbFilesize / 1024 , 4 );
    //         if( ( $document = \App\Models\Regulator\Regulator::find($request->id) ) !== null ){
    //             list($year,$month,$day) = explode('-',$document->year);
    //             $uniqeName = Storage::disk('regulator')->putFile( '' , new File( $_FILES['files']['tmp_name'] ) );
    //             $document->pdf = $uniqeName ;
    //             $document->save();
    //             if( Storage::disk('regulator')->exists( $document->pdf ) ){
    //                 $document->pdf = Storage::disk("regulator")->url( $document->pdf  );
    //                 return response([
    //                     'record' => $document ,
    //                     'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
    //                 ],200);
    //             }else{
    //                 return response([
    //                     'record' => $document ,
    //                     'message' => 'មិនមានឯកសារយោងដែលស្វែងរកឡើយ។'
    //                 ],403);
    //             }
    //         }else{
    //             return response([
    //                 'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់ឯកសារយោង។'
    //             ],403);
    //         }
    //     }else{
    //         return response([
    //             'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
    //         ],403);
    //     }
    // }
    
    public function create(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        /**
         * Save information of the regulator and its related information
         */
        
        $record = RecordModel::create([
            'title' => $request->title?? ''  ,
            'objective' => $request->objective?? '' ,
            'content' => $request->content?? '' ,
            'created_by' => $user->id ,
            'updated_by' => $user->id
        ]);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( isset( $request->id ) && $request->id > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            /**
             * Save information of the regulator and its related information
             */
            if( $record->update([
                'title' => $request->title?? ''  ,
                'objective' => $request->objective?? '' ,
                'content' => $request->content?? '' ,
                'updated_by' => $user->id
            ]) ){
                $legalDraft->with('regulator')->with('meetings');
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
        $legalDraft = RecordModel::find($request->id);
        if( $legalDraft == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $legalDraft->with('regulator')->with('meetings');
        return response()->json([
            'record' => $legalDraft ,
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
        $legalDraft = RecordModel::find($request->id);
        if( $legalDraft == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារស្វែករកបានជោគជ័យ។'
            ],201);
        }
        $tempRecord = $legalDraft;
        if( $legalDraft->delete() ){
            /**
             * Delete all the related documents own by this regulator
             */
            // if( $tempRecord->pdf !== null && $tempRecord->pdf !=="" && Storage::disk('regulator')->exists( $tempRecord->pdf ) ){
            //     Storage::disk("document")->delete( $tempRecord->pdf  );
            // }
            $tempRecord->with('regulator')->with('meetings');
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
