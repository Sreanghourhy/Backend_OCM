<?php
namespace App\Http\Controllers\Api\Admin\Law\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Law\Book\Book AS RecordModel;
use App\Models\Law\Book\Kunty;
use App\Models\Law\Book\Chapter;
use App\Models\Law\Book\Matika;
use App\Models\Law\Book\Part;
use App\Models\Law\Book\Section;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CrudController;

class BookController extends Controller
{
    private $selectedFields ;

    public function __construct(){
        $this->selectedFields = ['id', 'title','description', 'color' , 'cover' , 'complete' , 'created_by', 'updated_by' , 'pdf' , 'created_at', 'updated_at' , 'active'] ;
    }

    /** Get a list of Archives */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;


        $queryString = [
            // "where" => [
            //     'default' => [
            //         [
            //             'field' => 'type_id' ,
            //             'value' => $type === false ? "" : $type
            //         ]
            //     ],
            //     'in' => [] ,
            //     'not' => [] ,
            //     'like' => [
            //         [
            //             'field' => 'number' ,
            //             'value' => $number === false ? "" : $number
            //         ],
            //         [
            //             'field' => 'year' ,
            //             'value' => $date === false ? "" : $date
            //         ]
            //     ] ,
            // ] ,
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
                    'title', 'description' 
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, 
            // Selected fields
            $this->selectedFields,
            // Fields with callback
            [
                'meaning' => function($record){
                    return html_entity_decode( strip_tags( $record->meaning ) );
                } ,
                'title' => function($record){
                    return html_entity_decode( strip_tags( $record->title ) );
                }
            ],
            // Rename fields
            false,
            // Extra fields
            [
                'total_kunties' => function($record){ return $record->kunties != null ? $record->kunties()->count() : 0 ; } ,
                'total_matikas' => function($record){ return $record->matikas != null ? $record->matikas()->count() : 0 ; } ,
                'total_chapters' => function($record){ return $record->chapters != null ? $record->chapters()->count() : 0 ; } ,
                'total_parts' => function($record){ return $record->parts != null ? $record->parts()->count() : 0 ; } ,
                'total_sections' => function($record){ return $record->sections != null ? $record->sections()->count() : 0 ; } ,
                'total_matras' => function($record){ return $record->matras != null ? $record->matras()->count() : 0 ; }
            ],
            // Storage driver
            'public'
        );
        
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'createdBy' => [ 'id' , 'firstname', 'lastname' ,'username'] ,
            'updatedBy' => [ 'id' , 'firstname', 'lastname', 'username'] , 
            'references' => [ 'id', 'fid' , 'title' , 'objective', 'year' , 'pdf' , 'publish' , 'active' , 'created_by' , 'updated_by' , 'accessibility' ]
        ]);

        $builder = $crud->getListBuilder();

        /** Filter the record by the user role */
        // if( ( $user = $request->user() ) !== null ){
        //     /** In case user is the administrator, all archives will show up */
        //     if( array_intersect( $user->roles()->pluck('id')->toArray() , [2,3,4] ) ){
        //         /** In case user is the super, auditor, member then the archives will show up if only that archives are own by them */
        //         $builder->where('created_by',$user->id);
        //     }else{
        //         /** In case user is the customer */
        //         /** Filter archives by its type before showing to customer */
        //     }
        // }

        $responseData = $crud->pagination(true, $builder,[
            'description' => function($description){
                return html_entity_decode( strip_tags( $description ) );
            } ,
            'title' => function($title){
                return html_entity_decode( strip_tags( $title ) );
            }
        ]);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /** Create a new Regulator */
    public function store(Request $request){
        if( ($user = $request->user() ) !== null ){
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['created_at'] = $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['created_by'] = $input['updated_by'] = $user->id;
            $request->merge($input);
            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'kunty_id', 'book_id', 'created_by', 'updated_by']);
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
    /** Updating the Regulator */
    public function update(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['updated_by'] = $user->id;
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, ['id', 'number', 'title', 'book_id',  'kunty_id', 'kunty', 'chapter_id', 'chapter', 'part_id', 'part', 'section_id', 'section', 'created_by', 'author', 'updated_by', 'editor']);
            if ($crud->update() !== false) {
                $record = $crud->formatRecord($crud->read());
                return response()->json([
                    'record' => $record,
                    'ok' => true ,
                    'message' => __("crud.update.succeed")
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
    /** Updating the Regulator */
    public function read(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $this->selectedFields[] = 'pdfs' ;
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            $crud->setRelationshipFunctions([
                'units' => false
            ]);
            if (($record = $crud->read()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'ok' => true ,
                    'message' => __("crud.read.success")
                ]);
            }
            return response()->json([
                'ok' => false,
                'message' => __("crud.read.failed")
            ]);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Get kunties of the Regulator */
    public function kunties(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $this->selectedFields[] = 'pdfs' ;
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            // $crud->setRelationshipFunctions([
            //     'units' => false ,
            //     'kunties' => ['id','title','number']
            // ]);
            if (($record = $crud->read()) !== false) {
                // $record = $crud->formatRecord($record);
                return response()->json([
                    'records' => $record->kunties,
                    'ok' => true ,
                    'message' => __("crud.read.success")
                ]);
            }
            return response()->json([
                'ok' => false,
                'message' => __("crud.read.failed")
            ]);
        }
        return response()->json([
            'records' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Get matikas of the Regulator */
    public function matikas(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $this->selectedFields[] = 'pdfs' ;
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            $crud->setRelationshipFunctions([
                'units' => false ,
                'matikas' => [ 'id' , 'number' , 'title' ]
            ]);
            if (($record = $crud->read()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'records' => $record,
                    'ok' => true ,
                    'message' => __("crud.read.success")
                ]);
            }
            return response()->json([
                'ok' => false,
                'message' => __("crud.read.failed")
            ]);
        }
        return response()->json([
            'records' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    
    /** Delete an Regulator */
    public function delete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['updated_by'] = $user->id;
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            $record = $crud->read();
            if ( $crud->delete() !== false) {
                /** Delete its structure and matras too */
                Storage::disk(env('STORAGE_DRIVER','public'))->delete($record->pdfs);
                return response()->json([
                    'ok' => true ,
                    'message' => __("crud.delete.success")
                ]);
            }
            return response()->json([
                'ok' => false,
                'message' => __("crud.delete.failed")
            ]);
        }
        return response()->json([
            'ok' => false,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Upload file */
    public function uploadCover(Request $request){
        $user = \Auth::user();
        if( $user ){
            $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $book = \App\Models\Law\Book\Book::find($request->id) ) !== null ){
                $uniqeName = Storage::disk('public')->putFile( 'book/covers' , new File( $_FILES['files']['tmp_name'] ) );
                $book->cover = $uniqeName ;
                $book->save();
                if( Storage::disk('public')->exists( $book->cover ) ){
                    $book->cover = Storage::disk("public")->url( $book->cover  );
                    return response([
                        'record' => $book ,
                        'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
                    ],200);
                }else{
                    return response([
                        'record' => $book ,
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
    
    /** Check duplicate Regulator */
    public function exists(Request $request){
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields  );
            if ( ($record = $crud->exists(['fid','year'],true)) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.duplicate.no")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.duplicate.yes")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Active the record */
    public function active(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields  );
            if ($crud->booleanField('active', 1)) {
                $record = $crud->formatRecord($record = $crud->read());
                return response(
                    [
                        'record' => $record,
                        'ok' => true ,
                        'message' => 'Activated !'
                    ]);
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]);
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Unactive the record */
    public function unactive(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            if ( $crud->booleanField('active', 0) ) {
                $record = $crud->formatRecord($record = $crud->read());
                // User does exists
                return response(
                    [
                        'record' => $record,
                        'ok' => true ,
                        'message' => 'Deactivated !'
                    ]);
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]);
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Active the record */
    public function complete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields  );
            if ($crud->booleanField('complete', 1)) {
                $record = $crud->formatRecord($record = $crud->read());
                return response(
                    [
                        'record' => $record,
                        'ok' => true ,
                        'message' => 'Completed !'
                    ]);
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]);
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Unactive the record */
    public function uncomplete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            if ( $crud->booleanField('complete', 0) ) {
                $record = $crud->formatRecord($record = $crud->read());
                // User does exists
                return response(
                    [
                        'record' => $record,
                        'ok' => true ,
                        'message' => 'Uncompleted !'
                    ]);
            } else {
                return response(
                    [
                        'ok' => false ,
                        'message' => 'There is not record matched !'
                    ]);
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /**
     * Remove file
     */
    public function removefile(Request $request)
    {
        $crud = new CrudController(new RecordModel(), $request, $this->selectedFields  );
        if (($record = $crud->removeFile('pdfs')) != null) {
            $record = $crud->formatRecord( $record );
            return response()->json([
                'record' => $record ,
                'message' => __('crud.remove.file.success')
            ], 200);
        }
        return response()->json([
            'message' => __('crud.remove.file.success')
        ], 350);
    }
    /** Mini display */
    public function compactList(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $queryString = [
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'title','description'
                ]
            ],
            "order" => [
                'field' => 'title' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, ['id', 'title', 'description']);
        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder, [
            'field' => 'title' ,
            'callback' => function($val){ return strip_tags( $val ); }
        ]);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    public function content(Request $request){
        if (($user = $request->user()) !== null) {
            $book = RecordModel::select(['id','title','description'])->where('id',$request->id)->first();
            if ( $book !== null ) {
                return response()->json([
                    'book' => $book ,
                    'structure' => RecordModel::getContent($book->id) ,
                    'ok' => true ,
                    'message' => __("crud.read.success")
                ]);
            }
            return response()->json([
                'ok' => false,
                'message' => __("crud.read.failed")
            ]);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /***
     * Check type of level
     * kunty
     * matika
        chapter
        part
        section
     */

    private function _checkType($type){
        $model = new Kunty();
        switch ($type) {
            case 'kunty':
                $model = new Kunty();
                break;
            case 'matika':
                $model = new Matika();
                break;
            case 'capture':
                $model = new Capture();
                break;
            case 'part':
                $model = new Part();
                break;
            case 'section':
                $model = new Section();
                break;
            default:
                # code...
                break;
        }
        return $model;
    }
    /** Save Structure */
    public function saveStructure(Request $request,$id){
        dd($request->all(),$id);
        $type = $request->get('type',null);
        $title = $request->get('title',null);
        $number = $request->get('number',null);
        $status = 404;
        $responseData['message'] = __("crud.read.failed");
        if(!$type)  return response()->json(['message'=>__("crud.read.failed")], 404);
        $model = $this->_checkType($type);
        $model->archive_id = $id;
        $model->title = $title;
        $model->save();
        if($model){
            $responseData['message'] = __("crud.read.success");
            $status = 200;
        }
        return response()->json($responseData, $status);
    }
    public function references(Request $request){
        $book = isset( $request->book_id ) && intval( $request->book_id ) ? RecordModel::find( $request->book_id ) : null ;
        if( $book == null ) {
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់សៀវភៅដែលត្រូវភ្ជាប់ឯកសារយោង។'
            ],403);
        }
        $reference = isset( $request->regulator_id ) && intval( $request->regulator_id ) ? \App\Models\Regulator\Regulator::find( $request->regulator_id ) : null ;
        if( $reference == null ) {
            return response()->json([
                'ok' => false ,
                'message' => 'សូមភ្ជាប់ឯកសារយោង។'
            ],403);
        }
        $book->references()->toggle([$reference->id]);
        $book->references;
        return response()->json([
            'ok' => true ,
            'record' => $book ,
            'message' => 'ឯកសារយោងបានភ្ជាប់រួចរាល់។'
        ],200);
    }

    public function regulators(Request $request){

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $organizations = isset( $request->organizations ) ? array_filter( explode(',',$request->organizations) , function($organization){ return intval( $organization );} ) : false ;
        $signatures = isset( $request->signatures ) ? array_filter( explode(',',$request->signatures) , function($signature){ return intval( $signature ) ;}) : false ;
        $types = isset( $request->types ) ? array_filter( explode(',',$request->types) , function($type){ return intval( $type ) ;}) : false ;

        $queryString = [
            // "where" => [
            //     // 'default' => [
            //     //     [
            //     //         'field' => 'created_by' ,
            //     //         'value' => $user->id
            //     //     ]
            //     // ],
            //     // 'in' => [
            //     //     [
            //     //         'field' => 'type' ,
            //     //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
            //     //     ]
            //     // ] ,
            //     // 'not' => [
            //     //     [
            //     //         'field' => 'type' ,
            //     //         'value' => [4]
            //     //     ]
            //     // ] ,
            //     // 'like' => [
            //     //     [
            //     //         'field' => 'number' ,
            //     //         'value' => $number === false ? "" : $number
            //     //     ],
            //     //     [
            //     //         'field' => 'year' ,
            //     //         'value' => $date === false ? "" : $date
            //     //     ]
            //     // ] ,
            // ] ,
            "pivots" => [
                $types ?
                [
                    "relationship" => 'types',
                    "where" => [
                        // "in" => [
                        //     "field" => "type_id",
                        //     "value" => $types
                        // ],
                        "like" => 
                            $search === false ? []
                            : [
                                "field" => "name" ,
                                "value" => $search    
                            ]
                    ]
                ]
                : [] ,
                $signatures ?
                [
                    "relationship" => 'signatures',
                    "where" => [
                        // "in" => [
                        //     "field" => "signature_id",
                        //     "value" => $signatures
                        // ],
                        "like" => 
                            $search === false ? []
                            : [
                                "field" => "name" ,
                                "value" => $search    
                            ]
                    ]
                ]
                : [] ,
                $organizations ?
                [
                    "relationship" => 'organizations',
                    "where" => [
                        // "in" => [
                        //     "field" => "organization_id",
                        //     "value" => $organizations
                        // ],
                        "like" => 
                            $search === false ? []
                            : [
                                "field" => "name" ,
                                "value" => $search    
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
                    'objective', 'fid', 'year'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $selectFields = [
            'id',
            'fid' ,
            'title' ,
            'objective',
            'year' ,
            'pdf' ,
            'publish' ,
            'active' ,
            'created_by' ,
            'updated_by' ,
            'accessibility'
        ];

        $crud = new CrudController(new \App\Models\Regulator\Regulator(), $request, $selectFields,[
            /**
             * custom the value of the field
             */
            'pdf' => function($record){
                $record->pdf = ( $record->pdf !== "" && $record->pdf !== null && \Storage::disk('regulator')->exists( str_replace( 'regulators/' , '' , $record->pdf ) ) )
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

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'organizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'types' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'books' => [ 'id', 'name' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
}