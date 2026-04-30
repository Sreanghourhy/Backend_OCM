<?php

namespace App\Http\Controllers\Api\Admin\Regulator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Regulator\RegulatorParent as RecordModel;
use App\Http\Controllers\CrudController;


class RegulatorParentController extends Controller
{
    private $selectFields = [
        'id',
        'name' ,
        'desc' ,
        // 'amend',
        'image' ,
        // 'document_id' ,
        // 'document_parent_id' ,
        'pid'
    ];
    private $renameFields = [
        'pid' => 'parentId' ,
        'image' => 'imageUrl' ,  
    ];
    private $fieldsWithCallback = [];

    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 1000 ;
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
                // 'not' => [
                //     [
                //         'field' => 'document_type' ,
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
                    'name', 'desc', 'amend'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];
        if( isset( $request->parent_id ) ) {
            $queryString['where']['default'] = [
                'in' => [
                    [
                        'field' => 'parent_id' ,
                        'value' =>  $request->parent_id
                    ]
                ]
            ];
        }

        $request->merge( $queryString );

        $this->fieldsWithCallback = [
            // 'image' => function($image){
            //     $image = ( $image !== null && $image !== "" && \Storage::disk('public')->exists( $image ) )
            //     ? true
            //     // \Storage::disk('public')->url( $image ) 
            //     : false ;
            //     return $image ;
            // },
            'desc' => function($desc){
                return html_entity_decode( strip_tags( $desc ) );
            },
            'id' => function($r){ return $r->id . '' ; }
        ];
        $crud = new CrudController(new RecordModel(), $request, $this->selectFields, $this->fieldsWithCallback, $this->renameFields);
        // $crud->setRelationshipFunctions([
        //     /** relationship name => [ array of fields name to be selected ] */
        //     'parentRecord' => [ 'id' ,'name','amend' , 'desc' , 'image' , 'document_id' , 'document_parent_id']
        // ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['columns'] = ["desc","id","imageUrl","name","parentId"];
        // $responseData['csv'] = implode(',',$responseData['columns']). "{\n}" . implode( "\n" , $responseData['records']->map(function($record){ return $record['id'].','.$record['name'].','.$record['image'].','.$record['parentId'].','.$record['desc'] ; })->toArray() );
        // $responseData['csv'] = implode(',',$responseData['columns']). PHP_EOL . ( implode( PHP_EOL , array_map( function($record){ return $record->id.','.$record->name.','.$record->image.','.$record->parentId.','.$record->desc ; } , $responseData['records']->toArray() ) ) );
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
        $regulator = isset( $request->parent_id ) && $request->parent_id > 0 ? \App\Models\Regulator::find( $request->parent_id ) : false ;

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
                    'desc', 'name', 'amend'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];
        if( isset( $request->parent_id ) ) {
            $queryString['where']['default'] = [
                'in' => [
                    [
                        'field' => 'parent_id' ,
                        'value' =>  $request->parent_id
                    ]
                ]
            ];
        }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'parentRecord' => [ 'id' ,'name','amend' , 'desc' , 'image' , 'document_id' , 'document_parent_id']
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder,[
                'image' => function($image){
                    $image = ( $image !== "" && \Storage::disk('public')->exists( $image ) )
                    ? true
                    // \Storage::disk('public')->url( $image ) 
                    : false ;
                    return $image ;
                },
                'desc' => function($desc){
                    return html_entity_decode( strip_tags( $desc ) );
                }
            ]
        );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    public function create(Request $request){
        /**
         * Save information of the regulator and its related information
         */
        
        $record = RecordModel::create([
            'parent_id' => $request->parent_id ,
            'document_id' => $request->document_id ,
            'name' => $request->name ,
            'document_parent_id' => $request->document_parent_id ,
            'desc' => $request->desc ,
            'image' => $request->image
        ]);
        /**
         * Upload pdf document(s) of the regulator
         */
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        if( isset( $request->id ) && $request->id > 0 ){
            $record = RecordModel::find($request->id);
            $responseData['message'] = __("crud.read.success");
            $responseData['ok'] = true ;
            $responseData['record'] = $record->update([
                'parent_id' => $request->parent_id ,
                'document_id' => $request->document_id ,
                'name' => $request->name ,
                'document_parent_id' => $request->document_parent_id ,
                'desc' => $request->desc ,
                'image' => $request->image
            ]) ;
            return response()->json($responseData, 200);
        }
        $responseData['message'] = __("crud.save.failed");
        $responseData['ok'] = false ;
        return response()->json($responseData, 201);
    }
    public function linkRegulator(Request $request){
        if( isset( $request->id ) && $request->id > 0 ){
            $record = RecordModel::find($request->id);
            $responseData['message'] = __("crud.read.success");
            $responseData['ok'] = true ;
            $responseData['record'] = $record->update([
                'document_id' => $request->document_id
            ]) ;
            return response()->json($responseData, 200);
        }
        $responseData['message'] = __("crud.save.failed");
        $responseData['ok'] = false ;
        return response()->json($responseData, 201);
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
        $record = RecordModel::find($request->id);
        if( $record == null ){
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
