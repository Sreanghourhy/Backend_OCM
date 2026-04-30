<?php

namespace App\Http\Controllers\Api\Law\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Law\Book\Matra AS RecordModel;
use Illuminate\Http\File;
use App\Http\Controllers\CrudController;

class MatraController extends Controller
{
    /** Get a list of matras */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $book_id = isset( $request->book_id ) && intval( $request->book_id ) ? $request->book_id : false ;
        $kunty_id = isset( $request->kunty_id ) && intval( $request->kunty_id ) ? $request->kunty_id : false ;
        $matika_id = isset( $request->matika_id ) && intval( $request->matika_id ) ? $request->matika_id : false ;
        $chapter_id = isset( $request->chapter_id ) && intval( $request->chapter_id ) ? $request->chapter_id : false ;
        $part_id = isset( $request->part_id ) && intval( $request->part_id ) ? $request->part_id : false ;
        $section_id = isset( $request->section_id ) && intval( $request->section_id ) ? $request->section_id : false ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'book_id' ,
                        'value' => $book_id === false ? "" : $book_id
                    ],
                    [
                        'field' => 'kunty_id' ,
                        'value' => $kunty_id === false ? "" : $kunty_id
                    ],
                    [
                        'field' => 'matika_id' ,
                        'value' => $matika_id === false ? "" : $matika_id
                    ],
                    [
                        'field' => 'chapter_id' ,
                        'value' => $chapter_id === false ? "" : $chapter_id
                    ],
                    [
                        'field' => 'part_id' ,
                        'value' => $part_id === false ? "" : $part_id
                    ],
                    [
                        'field' => 'section_id' ,
                        'value' => $section_id === false ? "" : $section_id
                    ],
                ],
                // 'in' => [] ,
                // 'not' => [] ,
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
            //     // $unit ?
            //     // [
            //     //     "relationship" => 'units',
            //     //     "where" => [
            //     //         "in" => [
            //     //             "field" => "id",
            //     //             "value" => [$request->unit]
            //     //         ],
            //     //     // "not"=> [
            //     //     //     [
            //     //     //         "field" => 'fieldName' ,
            //     //     //         "value"=> 'value'
            //     //     //     ]
            //     //     // ],
            //     //     // "like"=>  [
            //     //     //     [
            //     //     //        "field"=> 'fieldName' ,
            //     //     //        "value"=> 'value'
            //     //     //     ]
            //     //     // ]
            //     //     ]
            //     // ]
            //     // : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'number','title', 'meaning'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, ['id', 'number','title', 'meaning' , 'book_id', 'kunty_id', 'matika_id', 'chapter_id' , 'part_id', 'section_id' , 'created_by' , 'updated_by' ]);
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
        
        $builder->whereHas('book',function($bookQuery){
            $bookQuery->where('complete',1)->where('active',1);
        });

        // /** Filter by regulator id */
        // if( $request->book_id > 0 ){
        //     $builder = $builder->where('book_id',$request->book_id);
        // }

        /** Filter the record by the user role */
        // if( ( $user = $request->user() ) !== null ){
            /** In case user is the administrator, all archives will show up */
            // if( array_intersect( $user->roles()->pluck('id')->toArray() , [2,3,4] ) ){
                /** In case user is the super, auditor, member then the archives will show up if only that archives are own by them */
                // $builder->where('created_by',$user->id);
            // }else{
                /** In case user is the customer */
                /** Filter archives by its type before showing to customer */
        //     }
        // }

        $responseData = $crud->pagination(true, $builder,[
            'meaning' => function($meaning){
                return html_entity_decode( strip_tags( $meaning ) );
            } ,
            'title' => function($title){
                return html_entity_decode( strip_tags( $title ) );
            }
        ]);

        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;
        return response()->json($responseData);
    }
    /** Create a new Archive */
    public function store(Request $request){
        if( ($user = $request->user() ) !== null ){
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['created_at'] = $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['created_by'] = $input['updated_by'] = $user->id;
            $request->merge($input);
            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator', 'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if (($record = $crud->create()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'ok' => true ,
                    'message' => __("crud.save.success")
                ]);
            }
            return response()->json([
                'ok' => false ,
                'message' => __("crud.save.failed")
            ]);
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
        
    }
    /** Updating the archive */
    public function update(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['updated_by'] = $user->id;
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id',  'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if ($crud->update() !== false) {
                $record = $crud->formatRecord($crud->read());
                return response()->json([
                    'record' => $record,
                    'ok' => true ,
                    'message' => __("crud.update.success")
                ]);
            }
            return response()->json([
                'ok' => false ,
                'message' => __("crud.update.failed")
            ]);
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Updating the archive */
    public function read(Request $request)
    {
        $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator' ,  'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
        if (($record = $crud->read()) !== false) {
            $record->book;
            $record->kunty;
            $record->matika;
            $record->chapter;
            $record->part;
            $record->section;
            $record->author;
            $record->editor;
            $record->folders;
            $record->favorites;
            $record->section;
            // $record = $crud->formatRecord($record);
            return response()->json([
                'record' => $record,
                'ok' => true ,
                'message' => __("crud.read.success")
            ]);
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.read.failed")
        ]);
    }
    /** Reading an archive */
    public function delete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['updated_by'] = $user->id;
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator' , 'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if (($record = $crud->delete()) !== false) {
                return response()->json([
                    'ok' => true ,
                    'message' => __("crud.delete.success")
                ]);
            }
            return response()->json([
                'ok' => false ,
                'message' => __("crud.delete.failed")
            ]);
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Check duplicate archive */
    public function exists(Request $request){
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator' , 'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if ( ($record = $crud->exists(['fid','year'],true)) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'ok' => true ,
                    'message' => __("crud.duplicate.no")
                ]);
            }
            return response()->json([
                'ok' => false ,
                'message' => __("crud.duplicate.yes")
            ]);
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Active the record */
    public function active(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator' , 'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if ($crud->booleanField('active', 1)) {
                $record = $crud->formatRecord($record = $crud->read());
                return response(
                    [
                        'ok' => true ,
                        'message' => 'Activated !'
                    ]
                );
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]
                );
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Unactive the record */
    public function unactive(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'meaning', 'book_id', 'regulator' , 'kunty_id', 'kunty', 'matika_id', 'matika', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if ( $crud->booleanField('active', 0) ) {
                $record = $crud->formatRecord($record = $crud->read());
                // User does exists
                return response(
                    [
                        'ok' => true ,
                        'message' => 'Deactivated !'
                    ]
                );
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]
                );
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Mini display */
    public function compactList(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $book_id = isset( $request->book_id ) && $request->book_id >0 ? $request->book_id : false ;
        $kunty_id = isset( $request->kunty_id ) && $request->kunty_id >0 ? $request->kunty_id : false ;
        $matika_id = isset( $request->matika_id ) && $request->matika_id >0 ? $request->matika_id : false ;
        $chapter_id = isset( $request->chapter_id ) && $request->chapter_id >0 ? $request->chapter_id : false ;
        $part_id = isset( $request->part_id ) && $request->part_id >0 ? $request->part_id : false ;
        $section_id = isset( $request->section_id ) && $request->section_id >0 ? $request->section_id : false ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'book_id' ,
                        'value' => $book_id === false ? "" : $book_id
                    ],
                    [
                        'field' => 'kunty_id' ,
                        'value' => $kunty_id === false ? "" : $kunty_id
                    ],
                    [
                        'field' => 'matika_id' ,
                        'value' => $matika_id === false ? "" : $matika_id
                    ],
                    [
                        'field' => 'chapter_id' ,
                        'value' => $chapter_id === false ? "" : $chapter_id
                    ],
                    [
                        'field' => 'part_id' ,
                        'value' => $part_id === false ? "" : $part_id
                    ],
                    [
                        'field' => 'section_id' ,
                        'value' => $section_id === false ? "" : $section_id
                    ],
                ],
                // 'in' => [] ,
                // 'not' => [] ,
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
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'number','title'
                ]
            ],
            "order" => [
                'field' => 'title' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, ['id', 'number','title']);
        $responseData['records'] = $crud->getListBuilder()->get();
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;
        return response()->json($responseData);
    }
    /**
     * Get matras of a regulator
     */
    public function ofBook(Request $request){
        if( $request->book_id > 0 ){
            /** Format from query string */
            $book_id = isset( $request->book_id ) && $request->book_id >0 ? $request->book_id : false ;
            $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
            $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
            $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
            $kunty_id = isset( $request->kunty_id ) && $request->kunty_id >0 ? $request->kunty_id : false ;
            $matika_id = isset( $request->matika_id ) && $request->matika_id >0 ? $request->matika_id : false ;
            $chapter_id = isset( $request->chapter_id ) && $request->chapter_id >0 ? $request->chapter_id : false ;
            $part_id = isset( $request->part_id ) && $request->part_id >0 ? $request->part_id : false ;
            $section_id = isset( $request->section_id ) && $request->section_id >0 ? $request->section_id : false ;

            $queryString = [
                "where" => [
                    'default' => [
                        [
                            'field' => 'book_id' ,
                            'value' => $book_id === false ? "" : $book_id
                        ],
                        [
                            'field' => 'kunty_id' ,
                            'value' => $kunty_id === false ? "" : $kunty_id
                        ],
                        [
                            'field' => 'matika_id' ,
                            'value' => $matika_id === false ? "" : $matika_id
                        ],
                        [
                            'field' => 'chapter_id' ,
                            'value' => $chapter_id === false ? "" : $chapter_id
                        ],
                        [
                            'field' => 'part_id' ,
                            'value' => $part_id === false ? "" : $part_id
                        ],
                        [
                            'field' => 'section_id' ,
                            'value' => $section_id === false ? "" : $section_id
                        ],
                    ],
                ] ,
                "pagination" => [
                    'perPage' => $perPage,
                    'page' => $page
                ],
                "search" => $search === false ? [] : [
                    'value' => $search ,
                    'fields' => [
                        'number','title', 'meaning'
                    ]
                ],
                "order" => [
                    'field' => 'id' ,
                    'by' => 'asc'
                ],
            ];

            $request->merge( $queryString );

            $crud = new CrudController(new RecordModel(), $request, ['id', 'number','title', 'meaning' , 'book_id', 'kunty_id', 'matika_id', 'chapter_id' , 'part_id', 'section_id' , 'created_by' , 'updated_by' , 'active' ]);
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
            $responseData['ok'] = true;
            return response()->json($responseData);
        }
        return response()->json(
            [
                'ok' => false ,
                'message' => __("crud.read.failed")
            ]
        );
    }
    /**
     * User Favorited Matras
     */
    public function ofUser(Request $request){
        $user = \Auth::user();
        if( $user == null ) return response()->json( [
            'ok' => false ,
            'message' => "សូមចូលប្រើប្រព័ន្ធជាមុនសិន"
        ] , 403);
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $book_id = isset( $request->book_id ) && intval( $request->book_id ) ? $request->book_id : false ;
        $kunty_id = isset( $request->kunty_id ) && intval( $request->kunty_id ) ? $request->kunty_id : false ;
        $matika_id = isset( $request->matika_id ) && intval( $request->matika_id ) ? $request->matika_id : false ;
        $chapter_id = isset( $request->chapter_id ) && intval( $request->chapter_id ) ? $request->chapter_id : false ;
        $part_id = isset( $request->part_id ) && intval( $request->part_id ) ? $request->part_id : false ;
        $section_id = isset( $request->section_id ) && intval( $request->section_id ) ? $request->section_id : false ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'book_id' ,
                        'value' => $book_id === false ? "" : $book_id
                    ],
                    [
                        'field' => 'kunty_id' ,
                        'value' => $kunty_id === false ? "" : $kunty_id
                    ],
                    [
                        'field' => 'matika_id' ,
                        'value' => $matika_id === false ? "" : $matika_id
                    ],
                    [
                        'field' => 'chapter_id' ,
                        'value' => $chapter_id === false ? "" : $chapter_id
                    ],
                    [
                        'field' => 'part_id' ,
                        'value' => $part_id === false ? "" : $part_id
                    ],
                    [
                        'field' => 'section_id' ,
                        'value' => $section_id === false ? "" : $section_id
                    ],
                ],
                // 'in' => [] ,
                // 'not' => [] ,
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
            //     // $unit ?
            //     // [
            //     //     "relationship" => 'units',
            //     //     "where" => [
            //     //         "in" => [
            //     //             "field" => "id",
            //     //             "value" => [$request->unit]
            //     //         ],
            //     //     // "not"=> [
            //     //     //     [
            //     //     //         "field" => 'fieldName' ,
            //     //     //         "value"=> 'value'
            //     //     //     ]
            //     //     // ],
            //     //     // "like"=>  [
            //     //     //     [
            //     //     //        "field"=> 'fieldName' ,
            //     //     //        "value"=> 'value'
            //     //     //     ]
            //     //     // ]
            //     //     ]
            //     // ]
            //     // : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'number','title', 'meaning'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, ['id', 'number','title', 'meaning' , 'book_id', 'kunty_id', 'matika_id', 'chapter_id' , 'part_id', 'section_id' , 'created_by' , 'updated_by' ]);
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

        // Filter matra to get only the favorited matra of the authenticated user
        $favoritedMatraIds = $user->favoriteMatras()->pluck('matra_id')->toArray();
        $builder->whereIn('id', $favoritedMatraIds );

        $responseData = $crud->pagination(true, $builder,[
            'meaning' => function($meaning){
                return html_entity_decode( strip_tags( $meaning ) );
            } ,
            'title' => function($title){
                return html_entity_decode( strip_tags( $title ) );
            }
        ]);

        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;
        return response()->json($responseData);
    }
}
