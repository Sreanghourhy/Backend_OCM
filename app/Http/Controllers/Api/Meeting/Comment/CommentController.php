<?php

namespace App\Http\Controllers\Api\Meeting\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting\MeetingComment as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    private $selectFields = [
        'id',
        'meeting_id' ,
        'people_id' ,
        'comment',
        'pdfs' ,
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
                    'comment'
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
            'pdfs' => function($record){
                if( is_array( $record->pdfs ) && count( $record->pdfs ) ){
                    foreach( $record->pdfs AS $index => $pdf ){
                        if( $pdf !== "" && $pdf !== null && \Storage::disk('comment')->exists( $pdf ) ) return true ;
                    }
                    return false ;
                }else{
                    return ( $record->pdfs !== "" && $record->pdfs !== null && \Storage::disk('comment')->exists( $record->pdfs ) ) ? true : false ;
                }
            },
           'comment' => function($record){
                    return html_entity_decode( strip_tags( $record->comment ) );
                }
            ]
        );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'meeting' => [ 'id' , 'objective' , 'start' , 'end' , 'actual_start' , 'actual_end' ] ,
            'people' => [ 'id' , 'firstname' , 'lastname' ] ,
            'createdBy' => [ 'id' , 'firstname' , 'lastname' ] ,
            'updatedBy' => [ 'id' , 'firstname' , 'lastname' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    public function create(Request $request){
        /**
         * Save information of the regulator and its related information
         */
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        $record = RecordModel::create([
            'meeting_id' => $request->meeting_id?? 0,
            'people_id' => $request->people_id?? 0 ,
            'comment' => $request->comment?? '',
            'created_by' => $user->id  ,
            'updated_by' => $user->id 
        ]);
        
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
                'meeting_id' => $request->meeting_id?? 0,
                'people_id' => $request->people_id?? 0 ,
                'comment' => $request->comment?? '',
                'updated_by' => $user->id 
            ]) ){
                $responseData['message'] = __("crud.read.success");
                $responseData['ok'] = true ;
                $responseData['record'] = $record ;
                return response()->json($responseData, 200);
            }else{
                return response()->json([
                    'message' => 'មានបញ្ហាក្នុងការរក្សារព័ត៌មាន។'
                ], 403);    
            }
        }else{
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់។'
            ], 403);
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],201);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ទិន្នន័យដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $record->with('meeting')->with('people');
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
        ],201);
    }

    public function destroy(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],201);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ទិន្នន័យនេះមិនមានឡើយ។'
            ],500);
        }
        $record->with('meeting')->with('people');
        $tempRecord = $record;
        if( $record->delete() ){
            /**
             * Delete all the related documents own by this regulator
             */
            if( $tempRecord->pdfs !== null && $tempRecord->pdfs !=="" && Storage::disk('comment')->exists( $tempRecord->pdfs ) ){
                Storage::disk("comment")->delete( $tempRecord->pdfs  );
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
}
