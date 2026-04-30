<?php

namespace App\Http\Controllers\Api\Law\Book;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use App\Models\Law\Book\Matra;
use App\Models\Folder\Folder as RecordModel;
use Illuminate\Support\Facades\Auth;


class FolderController extends Controller
{
    /**
     * Listing function
     */
    private $selectFields = [
        'id',
        'name' ,
        'user_id' ,
        'created_at' ,
        'updated_at' ,
        'accessibility'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = Auth::user() != null ? \Auth::user() : null ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សុំបញ្ជាក់ពីម្ចាស់បញ្ជីឯកសារ។'
            ],403);
        }
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'user_id' ,
                        'value' => $user->id
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
            'matras' => [ 'id' ,'number' , 'title' , 'meaning' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['user'] = $user ;
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
            'matras' => [ 'id' , 'number' , 'title' , 'meaning' ] ,
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

        // Check the user
        $user = \Auth::user();
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់ពីម្ចាស់បញ្ជីមាត្រា។' 
            ],403);
        }
        
        $folderQueryBuilder = $user->folders()->whereHas('matras');

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $folderQueryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'name', 'LIKE' , "%".$term."%") ;
                    }
                });
            }
        }

        $records = $folderQueryBuilder->orderby('name','asc')->get()
            // ->map( function ($record, $index) {
            //     $record->matras;
            //     return $record ;
            // })
        ;

        return response([
            'ok' => true ,
            'records' => $records ,
            'message' => $records->count() > 0 ? " មានបញ្ជីមាត្រាចំនូួន ៖ " . $records->count(): "មិនមានបញ្ជីមាត្រាត្រូវជាមួយការស្វែងរកនេះឡើយ !"
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
            $folder->matras ;
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
            $folder->matras ;
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
    // delete the folder 
    public function delete(Request $request){
        if( $request->id != "" 
         // && Auth::user() != null 
        ){
            $folder = RecordModel::find($request->id);
            if( $folder != null ){
                $record = $folder ;
                // Check for the regulators within the folder
                // If there is/are regulators within the folder then notify user first
                // process delete , also delete the related document within this folder [Note: we only delete the relationship of folder and document]
                if( $folder->matrasFolder !== null && $folder->matrasFolder->count() ){
                    foreach( $folder -> matrasFolder as $matraFolder ) $matraFolder -> delete ();
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
    // Add document from folder
    public function toggleMatra(Request $request){
        $folder = intval( $request->folder_id ) > 0 ? RecordModel::find( $request->folder_id ) : null ;
        if( $folder == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជីមាត្រាទេ។'
            ],403);
        }
        $matra = intval( $request->matra_id ) > 0 ? \App\Models\Law\Book\Matra::find( $request->matra_id ) : null ;
        if( $matra == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានមាត្រានេះទេ។'
            ],403);
        }
        $folder->matras()->toggle( [ $matra->id ] );
        return response([
            'ok' => true ,
            'record' => $folder ,
            'message' => "ជោគជ័យ។"
            ],
            200
        );
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],201);
        }
        $folder = RecordModel::find($request->id);
        if( $folder == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានឡើយ។'
            ],201);
        }
        return response()->json([
            'record' => $folder ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    /**
     * Listing regulators of the folder
     */
    public function matras(Request $request){
        $folder = RecordModel::find( $request->folder_id );
        if( $folder == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជីនេះឡើយ។'
            ],403);
        }
        $matraIds = $folder->matras->pluck('id')->toArray();
        if( empty( $matraIds ) ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានមាត្រា'
            ],200);
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
                            'value' => $matraIds
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
                    'title' , 'meaning'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController( new \App\Models\Law\Book\Matra() , $request, ['id', 'number','title', 'meaning' , 'book_id', 'kunty_id', 'matika_id', 'chapter_id' , 'part_id', 'section_id' , 'created_by' , 'updated_by' ] );
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            "book" => ['id','title','description'] ,
            "kunty" => ['id', 'number', 'title'],
            "matika" => ['id', 'number', 'title'],
            "chapter" => ['id', 'number', 'title'],
            "part" => ['id', 'number', 'title'],
            "section" => ['id', 'number', 'title'],
            'author' => ['id', 'firstname', 'lastname' ,'username'] ,
            'editor' => ['id', 'firstname', 'lastname', 'username'] ,
            'folders' => ['id', 'name', 'accessibility' , 'user_id' ] ,
            'favorites' => [ 'id' , 'firstname' , 'lastname' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        // $responseData['folderIds'] = $folderIds ;
        // $responseData['sql'] = $builder->toSql();
        return response()->json($responseData, 200);
    }
    public function accessibility(Request $request){
        $folder = RecordModel::find( $request->folder_id );
        if( $folder == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជីនេះឡើយ។'
            ],403);
        }
        $result = in_array( intVal( $request->mode ) , [ 0 , 1 , 2 , 4 ] ) != false ? $record->update(['accessibility'=> intVal( $request->mode ) ] ) : false ;
        return response()->json([
            'record' => $result == false ? null : $record ,
            'ok' =>  $result == false ? false : true ,
            'message' => $result == false ? "មានបញ្ហាក្នុងការកែប្រែ។" : 'បានកែរួចរាល់។'
        ], $result == false ? 403 : 200 );
    }
}
