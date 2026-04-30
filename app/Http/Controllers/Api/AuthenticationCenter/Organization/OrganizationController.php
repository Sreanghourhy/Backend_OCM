<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\Position\Position;
use App\Models\Organization\Organization as RecordModel;
use App\Models\Organization\OrganizationStructure;
use App\Models\Organization\OrganizationStructurePosition;


class OrganizationController extends Controller
{
    private $model = null ;
    private $fields = [ 'id','name','desp' , 'pid' , 'tpid' , 'prefix', 'record_index' , 'active' ] ;
    private $renameFields = [
        'pid' => 'parentId'
    ];
    public function __construct(){
        $this->model = new RecordModel();
    }
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? intval( $request->perPage ) : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? intval( $request->page ) : 1 ;
        $id = isset( $request->id ) && intval( $request->id ) > 0? intval( $request->id ) : false ;

        $root = $id > 0
            ? RecordModel::where('id',$id)->first()
            : null ;
        if( $root != null ){
            $root->parentNode;
            $root->totalChilds = $root->totalChildNodesOfAllLevels();
        }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],

                // 'in' => [] ,

                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                'like' => [
                    $root != null
                    ? [
                        'field' => 'tpid' ,
                        'value' => ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%"
                    ]
                    : [],
                    // [
                    //     'field' => 'year' ,
                    //     'value' => $date === false ? "" : $date
                    // ]
                ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'name' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields , [
            'totalChilds' => function($record){
                // return $record->totalChildNodesOfAllLevels();
                return 0 ;
            },
            'pid' => function($record){
                return $record->pid;
            }
        ] );

        $crud->setRelationshipFunctions([
            'parentNode' => [
                'id' , 'name'
            ],
            'childNodes' => [
                'id' , 'name'
            ]
        ]);

        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true , $builder );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    public function getStructureOrganizations(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? intval( $request->perPage ) : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? intval( $request->page ) : 1 ;
        $id = isset( $request->id ) && intval( $request->id ) > 0? intval( $request->id ) : false ;

        $root = $id > 0
            ? RecordModel::where('id',$id)->first()
            : null ;
        if( $root != null ){
            $root->parentNode;
            $root->totalChilds = $root->totalChildNodesOfAllLevels();
        }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],

                // 'in' => [] ,

                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                'like' => [
                    $root != null
                    ? [
                        'field' => 'tpid' ,
                        'value' => ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%"
                    ]
                    : [],
                    // [
                    //     'field' => 'year' ,
                    //     'value' => $date === false ? "" : $date
                    // ]
                ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'name' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields , [
            'totalChilds' => function($record){
                // return $record->totalChildNodesOfAllLevels();
                return 0 ;
            },
            'pid' => function($record){
                return $record->pid;
            }
        ] );

        $crud->setRelationshipFunctions([
            'parentNode' => [
                'id' , 'name'
            ],
            'childNodes' => [
                'id' , 'name'
            ]
        ]);

        $builder = $crud->getListBuilder();
        $builder->whereHas('structure');
        $responseData = $crud->pagination(true , $builder );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /**
     * Listing function
     */
    public function listByParent(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? intval( $request->perPage ) : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? intval( $request->page ) : 1 ;
        $id = intval( $request->id ) > 0 ? intval( $request->id ) : false ;
        $root = $id
            ? RecordModel::where('id',$id)->first()
            : RecordModel::where(
                function($query){
                        $query->whereNull( 'tpid' )->orWhere( 'tpid' , '' )->orWhere('tpid' ,0 );
                    }
                )
                ->where(
                    function($query){
                        $query->whereNull( 'pid' )->orWhere('pid' ,0 );
                    }
                )
                ->first();
        if( $root != null ){
            $root->totalChilds = $root->totalChildNodesOfAllLevels();
        }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],

                // 'in' => [] ,

                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                'like' => [
                    $root != null
                    ? [
                        'field' => 'tpid' ,
                        'value' => ( intval( $root->pid ) > 0 ? '%' . $root->pid.":" : '' ) . $root->id . "%"
                    ]
                    : [] ,
                    // [
                    //     'field' => 'year' ,
                    //     'value' => $date === false ? "" : $date
                    // ]
                ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields , [
            'totalChilds' => function($record){
                return $record->totalChildNodesOfAllLevels();
            }
        ] );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'leader' => [
                'id' , 'firstname' , 'lastname' , 'image'
                , 'organizations' => [ 'id' , 'name', 'desp' ]
                , 'positions' => [ 'id' , 'name', 'desp' ]
                , 'countesies' => [ 'id' , 'name', 'desp' ]
            ] ,
            'staffs' => [
                'id' , 'code' , 'official_date' , 'image' , 'phone' , 'email' ,
                'people' => [ 'id' , 'firstname' , 'lastname' ] ,
                'countesy' => [ 'id' , 'name' ] ,
                'position' => [ 'id' , 'name' ]
                // , 'organizations' => [ 'id' , 'name', 'desp' ]
                // , 'positions' => [ 'id' , 'name', 'desp' ]
                // , 'countesies' => [ 'id' , 'name', 'desp' ]
            ]
        ]);

        $builder = $crud->getListBuilder();

        // $builder->where('tpid', "LIKE" , ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%" );
        if( $root != null ) {
            $root->parentId = null ;
            $root->staffs;
        }

        $responseData = $crud->pagination(true , $builder );
        if( $root != null ) {
            $responseData['records'] = $responseData['records']->prepend( $root );
        }
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /** Mini display */
    public function compact(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 1000 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $id = isset( $request->id ) && intval( $request->id ) > 0? intval( $request->id ) : false ; // 163 គឺ រាជរដ្ឋាភិបាល

        $root = $id > 0
            ? RecordModel::where('id',$id)->first()
            : null ;
        if( $root != null ){
            $root->parentNode;
            $root->totalChilds = $root->totalChildNodesOfAllLevels();
        }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],

                // 'in' => [] ,

                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                'like' => [
                    $root != null
                    ? [
                        'field' => 'tpid' ,
                        'value' => ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%"
                    ]
                    : [],
                    // [
                    //     'field' => 'year' ,
                    //     'value' => $date === false ? "" : $date
                    // ]
                ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields );

        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true , $builder );

        $responseData['records'] = $responseData['records'];
        // ->prepend( $root );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
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
                'message' => 'មិនមានព័ត៌មាននេះឡើយ។'
            ],201);
        }
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    /**
     * Create an account
     */
    public function store(Request $request){
        // Get parent
        $parentNode = isset( $request->pid ) && intval( $request->pid ) > 0 ? RecordModel::find( $request->pid ) : null ;

        // let create organization without the parent organization
        // if( $parentNode == null ){
        //     return response()->json([
        //         'ok' => false ,
        //         'message' => 'ស្ថាប័នមេ នេះមិនមានឡើយ។'
        //     ],403);
        // }
        $record = strlen( $request->name ) > 0 ? RecordModel::where('keyname', str_replace( [ ' ' , '​' ] , '' , $request->name ) )->first() : null ;
        if( $record != null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឈ្មោះនេះមានរួចហើយ'
            ],403);
        }
        // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
        $record = RecordModel::create([
            'keyname' => str_replace( [ ' ' , '​' ] , '' , $request->name ) ,
            'name' => $request->name,
            'desp' => $request->desp ,
            'pid' => $parentNode == null ? null : $parentNode->id ,
            'tpid' => $parent != null ? ( $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id : "0:".$parentNode->id ) : "0",
            'image' => null ,
            'prefix' => $request->prefix??''
        ]);

        if( $record ){
            return response()->json([
                'record' => $record ,
                'ok' => true ,
                'message' => 'បង្កើតបានរួចរាល់'
            ], 200);

        }else {
            return response()->json([
                'user' => null ,
                'ok' => false ,
                'message' => 'មានបញ្ហា។'
            ], 201);
        }
    }
    /**
     * Update an account
     */
    public function update(Request $request){
        // Get parent
        $parentNode = intval( $request->pid ) > 0 ? RecordModel::find( $request->pid ) : 0 ;
        // if( $parentNode == null ){
        //     return response()->json([
        //         'ok' => false ,
        //         'message' => 'ស្ថាប័នមេនេះមិនមានឡើយ។'
        //     ],403);
        // }
        $record = strlen( $request->name ) > 0 ? RecordModel::where([
            'keyname' => str_replace( [ ' ' , '​' ] , '' , $request->name )
        ])->first() : null ;

        if( $record != null && $record->id != $request->id ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឈ្មោះនេះមានរួចហើយ'
            ],403);
        }
        $record = intval( $request->id ) > 0 ? RecordModel::find($request->id) : null ;
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ស្ថាប័ន។'
            ],403);
        }
        $updateData = [
            'keyname' => str_replace( [ ' ' , '​' ] , '' , $request->name ) ,
            'name' => $request->name ,
            'desp' => $request->desp ,
            'pid' => $parentNode == null ? null : $parentNode->id ,
            'tpid' => $parentNode != null ? ( $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id : "0:".$parentNode->id ) : "0",
            'prefix' => $request->prefix??''
        ];
        $record->update( $updateData );
        return response()->json([
            'record' => $record ,
            'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
            'ok' => true
        ], 200);
    }
    /**
     * Active function of the account
     */
    public function active(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->active = $request->active ;
            $record->save();
            // record does exists
            return response([
                'record' => $record ,
                'ok' => true ,
                'message' => 'ជោគជ័យ !'
                ],
                200
            );
        }else{
            // record does not exists
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'សូមទោស មិនមានទេ !'
                ],
                201
            );
        }
    }
    /**
     * Unactive function of the account
     */
    public function unactive(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->active = 0 ;
            $record->save();
            // Urecordser does exists
            return response([
                'ok' => true ,
                'record' => $record ,
                'message' => 'ជោគជ័យ !'
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'សូមទោសមិនមានទេ !' ],
                201
            );
        }
    }
    /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record ){
            $record->deleted_at = \Carbon\Carbon::now() ;
            $record->save();
            // User does exists
            return response([
                'ok' => true ,
                'record' => $record ,
                'message' => ' បានលុបដោយជោគជ័យ !' ,
                'ok' => true
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'record' => null ,
                'message' => 'សូមទោស ព័ត៌មាននេះមិនមានទេ !' ],
                201
            );
        }
    }
    /**
     * Create child
     */
    public function addChild(Request $request){
        $parent = intval( $request->pid ) > 0
            ? RecordModel::find($request->pid)
            : null ;
        $child = intval( $request->cid ) > 0
            ? RecordModel::find($request->cid)
            : null ;
        if( $parent == null || $child == null ){
            return response()->json([
                'ok' => false ,
                'message' => "សូមជ្រើសរើស អង្គភាពមេ និង អង្គភាពចំណុះ។"
            ],350);
        }
        $child->pid = $parent->id ;
        $child->tpid = $parent != null && $parent->tpid != "" ? $parent->tpid .':'. $parent->id : "0:".$parent->id ;

        $child->save();
        return response()->json([
            'child' => $child ,
            'parent' => $parent ,
            'ok' => true ,
            'message' => 'បានភ្ជាប់អង្គភាបចំណុះរួចរាល់។'
        ], 200);
    }
    /**
     * Get staffs of the organization
     */
    public function getStaffs(Request $request){
        $organization = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        if( $organization == null ){
            return response()->json([
                'message' => 'សូមបញ្ជាក់អង្គភាព។' ,
                'ok' => false
            ],500);
        }

        return response([
            'ok' => true ,
            // 'records' => $records ,
            // 'message' => count( $records ) > 0 ? " មានថតឯកសារចំនូួន ៖ " . count( $records ) : "មិនមានថតឯកសារត្រូវជាមួយការស្វែងរកនេះឡើយ !"
        ],200 );
    }
    /**
     * Reengineering structure of the organization with position
     */
    public function getStructure(Request $request){

        $organizationStructure = \App\Models\Organization\OrganizationStructure::whereNull('pid')
        ->orWhere('pid','0')
        ->orWhere('pid',0)
        ->first();
        // return response()->json( $organizationStructure->getOrganizationStructure() , 200 );

        $organizationStructure = intval( $request->organization_structure_id ) ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
        if( $organizationStructure == null ){
            return response()->json([
                'ok' => true ,
                'records' => [] ,
                'message' => 'មិនមានបញ្ជាក់អង្គភាពមេ។'
            ],200);
        }

        $organizationStructure = $organizationStructure->getStructure();
        $root_position = $organizationStructure['record']->rootPosition();

        return response()->json([
            'ok' => true ,
            // 'organizationStructure' =>  ,
            'sql' => $organizationStructure['query'] ,
            'record' => $organizationStructure['record'] ,
            'records' => $organizationStructure['records'] ,
            // 'record' => [
            //     'id' => $organizationStructure->id ,
            //     'pid' => $organizationStructure->pid ,
            //     // 'tpid' => $organizationStructure->tpid ,
            //     'organization' => $organizationStructure->organization ,
            //     // 'position' => $organizationStructure->position ,
            //     'total_jobs' => $organizationStructure->total_jobs ,
            //     'total_jobs_of_children_position' => $organizationStructure->total_jobs_of_children_position ,
            //     'total_jobs_of_parent_position' => $organizationStructure->total_jobs_of_parent_position ,
            //     'children' => $organizationStructure->children ,
            //     'root_position' => $organizationStructure->root_position
            // ],
            // 'record' => $organizationStructure['root_organization'] ,
            // 'records' => $organizationStructure['children'] ,
            'message' => 'រួចរាល់។'

        ],200);

        // $jobs = collect();
        // $organizationStructure->organization;
        // $organizationStructure->position;

        // $organizationStructure->total_jobs = $organizationStructure->total_unit_jobs = 0 ;
        // $organizationStructure->root_position = $organizationStructure->rootPosition();
        // if( $organizationStructure->root_position != null ){
        //     $organizationStructure->root_position->jobs = $organizationStructure->root_position->officerJobs()->select(['id','officer_id','countesy_id','start','end','organization_structure_position_id'])->orderby('id','desc')->get()->map(function( $job ) use(&$organizationStructure){
        //         $job->countesy;
        //         if( $job->officer != null ){
        //             if( $job->officer->people != null ){
        //                 if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
        //                     $organizationStructure->root_position->image = \Storage::disk('public')->url( $job->officer->people->image );
        //                 }
        //                 else if ( $job->officer->people->users != null ){
        //                     $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
        //                     if( $user != null && $user->avatar_url != null ){
        //                         $organizationStructure->root_position->image = \Storage::disk('public')->url( $user->avatar_url );
        //                     }
        //                 }
        //             }
        //         }
        //         return $job;
        //     });
        //     // Count the number of officer base on job position

        //     $organizationStructure->total_unit_jobs = $organizationStructure->root_position->total_jobs = $organizationStructure->root_position->total_unit_jobs = $organizationStructure->root_position->jobs->count();
        //     $organizationStructure->root_position->position;
        //     if( $organizationStructure->root_position->children != null ){
        //         $organizationStructure->root_position->children = $organizationStructure->root_position->getStructure($jobs);
        //         $organizationStructure->root_position->total_jobs += $organizationStructure->root_position->children->pluck('total_jobs')->sum();
        //     }
        //     $organizationStructure->total_jobs = $organizationStructure->root_position->total_jobs ;
        // }

        // if( $organizationStructure->children != null ){
        //     $organizationStructure->children = $organizationStructure->getStructure($jobs);
        //     $organizationStructure->total_jobs += $organizationStructure->children->pluck('total_jobs')->sum();
        // }

        // $jobs = $jobs->merge( $organizationStructure->root_position->jobs );
        // $officers = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery){
        //         $jobQuery->whereNull('deleted_at')->whereHas('organizationStructurePosition',function( $organizationStructurePositionQuery ){
        //             $organizationStructurePositionQuery->whereHas('organizationStructure');
        //         })
        //         ;
        //     })->whereNull('deleted_at')
        //     ->get()->map(function($officer){
        //         $officer->jobs = $officer->jobs->map(function($job){
        //             if( $job->organizationStructurePosition != null ){
        //                 $job->organizationStructurePosition->position;
        //                 if( $job->organizationStructure != null ){
        //                     $job->organizationStructure->organization;
        //                 }
        //             }
        //             return $job;
        //         });
        //         return $officer;
        //     })  ;

        // $job_officer_ids = $jobs->pluck('officer_id')->toArray();
        // $officer_ids = $officers->pluck('id')->toArray() ;
        // $not_included = array_diff( $officer_ids , $job_officer_ids ) ;
        // // return response()->json( [ 'total' => count( $not_included ) , 'diff' => $not_included ] );
        // $notIncludeOfficers = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery){
        //         $jobQuery->whereNull('deleted_at')->whereHas('organizationStructurePosition',function( $organizationStructurePositionQuery ){
        //             $organizationStructurePositionQuery->whereHas('organizationStructure');
        //         })
        //         ;
        //     })->whereNull('deleted_at')
        //     ->whereIn('id', $not_included )
        //     ->get()->map(function($officer){
        //         $officer->jobs = $officer->jobs->map(function($job){
        //             if( $job->organizationStructurePosition != null ){
        //                 $job->organizationStructurePosition->position;
        //                 if( $job->organizationStructure != null ){
        //                     $job->organizationStructure->organization;
        //                 }
        //             }
        //             return $job;
        //         });
        //         $officer->people;
        //         return $officer;
        //     })  ;
        // return response()->json([
        //     'ok' => true ,
        //     'record' => [
        //         'id' => $organizationStructure->id ,
        //         'pid' => $organizationStructure->pid ,
        //         'tpid' => $organizationStructure->tpid ,
        //         'organization' => $organizationStructure->organization ,
        //         'position' => $organizationStructure->position ,
        //         'total_jobs' => $organizationStructure->total_jobs ,
        //         'total_unit_jobs' => $organizationStructure->total_unit_jobs ,
        //         'children' => $organizationStructure->children ,
        //         'root_position' => $organizationStructure->root_position
        //     ],
        //     'jobs' => $jobs ,
        //     'job_officer_ids' => $job_officer_ids ,
        //     'officers' => $officers ,
        //     'notIncludeOfficers' => $notIncludeOfficers ,
        //     'not_included' => $not_included ,
        //     'officer_ids' => $officer_ids ,
        //     'message' => 'រួចរាល់។'

        // ],200);
    }
    public function readStructure(Request $request){
        $organizationStructure = intval( $request->organization_structure_id ) ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
        if( $organizationStructure == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានអង្គភាពនេះឡើយ។'
            ],500);
        }
        $organizationStructure->organization;
        return response()->json([
            'ok' => true ,
            'record' => $organizationStructure ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    /**
     * Create organization structure
     */
    public function addStructure(Request $request){
        $user = \Auth::user() ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អត្តសញ្ញាណ។'
            ],403);
        }
        // Get parent
        $parentNode = isset( $request->pid ) && intval( $request->pid ) > 0 ? OrganizationStructure::find( $request->pid ) : null ;
        // Get child
        $organization = isset( $request->organization_id ) && intval( $request->organization_id ) > 0 ? RecordModel::find( $request->organization_id ) : null ;
        if( $organization == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្ថាប័ន នេះមិនមានឡើយ។'
            ],500);
        }

        // One organization only can be used one
        $organizationStructure = OrganizationStructure::where('organization_id',$organization->id)->first() ;
        if( $organizationStructure != null ){
            // Get the root
            $tpid = explode( ':' , $organizationStructure->tpid );
            if(
                count( $tpid ) > 1 &&
                (
                    $root = (
                        ( $rootId = intval( $tpid[1] ) ) > 0
                            ? OrganizationStructure::find( $rootId )
                            : null
                    )
                )  != null
            ){
                // return with the existing OrganizationStructure
                $root->organization;
                return response()->json([
                    'ok' => true ,
                    'record' => $root ,
                    'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . '%' )->with('organization')->get() ,
                    'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
                ],200);
            }else{
                $organizationStructure->organization;
                return response()->json([
                    'ok' => true ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
                ],200);
            }
        }

        $organizationStructure = null ;
        if( $parentNode == null ){
            // This is root
            $organizationStructure = OrganizationStructure::where('organization_id',$organization->id)->where(function($query){
                $query->whereNull('pid')
                ->orWhere('pid',0);
            })->first() ;
            if( $organizationStructure == null ){
                $organizationStructure = OrganizationStructure::create([
                    'organization_id' => $organization->id ,
                    'pid' => 0 ,
                    'tpid' => $parentNode == null > 0 ? '0' : $parentNode->tpid .':'. $parentNode->id ,
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);

                // $organizationStructure->organization;
                // return response()->json([
                //     'ok' => true ,
                //     'record' => $organizationStructure ,
                //     'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                //     'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                // ],200);
                $tpid = explode( ':' , $organizationStructure->tpid );
                if(
                    count( $tpid ) > 1 &&
                    (
                        $root = (
                            ( $rootId = intval( $tpid[1] ) ) > 0
                                ? OrganizationStructure::find( $rootId )
                                : null
                        )
                    )  != null
                ){
                    // return with the existing OrganizationStructure
                    $root->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $root ,
                        'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                    ],200);
                }else{
                    $organizationStructure->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $organizationStructure ,
                        'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                    ],200);
                }

            }else{
                $organizationStructure->organization;
                return response()->json([
                    'ok' => false ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'មានរួចហើយ'
                ],200);
            }
        }else{
            $organizationStructure = $organization->structure()->where('pid',$parentNode->id)->where('organization_id',$organization->id)->first();
            if( $organizationStructure == null ){
                // This is child
                // $organizationStructure = $organization->structure()->create([
                $organizationStructure = OrganizationStructure::create([
                    'organization_id' => $organization->id ,
                    'pid' => $parentNode->id ,
                    'tpid' => $parentNode->tpid != null && strlen( $parentNode->tpid ) > 0 ? $parentNode->tpid .':'. $parentNode->id : '0:'.$parentNode->id ,
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
                // Update child list of parentnode
                $cids = strlen( trim($parentNode->cids) ) > 0 ? explode( ',' , trim($parentNode->cids) ) : [] ;
                $cids[] = $organizationStructure->id ;
                $parentNode->update([
                    'cids' => count( $cids ) > 1 ? implode(',',$cids) : $cids[0]
                ]);
                $tpid = explode( ':' , $organizationStructure->tpid );
                if(
                    count( $tpid ) > 1 &&
                    (
                        $root = (
                            ( $rootId = intval( $tpid[1] ) ) > 0
                                ? OrganizationStructure::find( $rootId )
                                : null
                        )
                    )  != null
                ){
                    // return with the existing OrganizationStructure
                    $root->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $root ,
                        'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលកូនអង្គភាពរួចហើយ។'
                    ],200);
                }else{
                    $organizationStructure->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $organizationStructure ,
                        'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលកូនអង្គភាពរួចហើយ។'
                    ],200);
                }
            }else{
                $tpid = explode( ':' , $organizationStructure->tpid );
                if(
                    count( $tpid ) > 1 &&
                    (
                        $root = (
                            ( $rootId = intval( $tpid[1] ) ) > 0
                                ? OrganizationStructure::find( $rootId )
                                : null
                        )
                    )  != null
                ){
                    // return with the existing OrganizationStructure
                    $root->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $root ,
                        'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . '%' )->with('organization')->get() ,
                        'message' => 'មានរួចហើយ'
                    ],200);
                }else{
                    $organizationStructure->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $organizationStructure ,
                        'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                        'message' => 'មានរួចហើយ'
                    ],200);
                }
            }
        }
    }
    public function deleteStructureNode(Request $request){
        $user = \Auth::user() ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អត្តសញ្ញាណ។'
            ],403);
        }
        $organizationStructure = intval( $request->id ) > 0 ? OrganizationStructure::find( $request->id ) : null ;
        if( $organizationStructure != null ){
            $cids = strlen( trim($organizationStructure->cids) ) > 0 ? explode( ',' , trim($organizationStructure->cids) ) : [] ;
            if( count( $cids ) > 0 ){
                foreach( $cids as $index => $id ){
                    if( ( $organizationStructure == OrganizationStructure::find( $id ) ) != null ){
                        return response()->json([
                            'ok' => false ,
                            'record' => $organizationStructure ,
                            'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                            'message' => 'មិនអាចលុបបានដោយសារមានអង្គភាពពីក្រោមបង្គាប់ ' . count( $cids ). '។'
                        ],500);
                    }
                }
            }
            $organizationStructure->update([ 'deleted_by' => $user->id ]);
            $organizationStructure->organization;
            return response()->json([
                'record' => $organizationStructure ,
                'ok' => $organizationStructure->delete() ,
                'message' => 'បាបលុបរួចរាល់។'
            ],200);
        }
        return response()->json([
            'ok' => true ,
            'message' => 'មិនមានអ្វីលុបឡើយ។'
        ],200);
    }
    /**
     * Position structure
     */
    public function getPositions(Request $request){
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) ? OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        if( $organizationStructurePosition == null ){
            return response()->json([
                'ok' => true ,
                'records' => [] ,
                'message' => 'មិនមានបញ្ជាក់តួនាទីមេ។'
            ],200);
        }

        // $table->integer('pid')->nullable()->comment('Parent id of this organization');
        // $table->text('tpid', 191)->nullable()->comment('The id of the parent which identify the whole type of them.');
        // $table->text('cids')->nullable()->comment('Children IDs will be store here.');
        // $table->string('image', 191)->nullable(true);
        // $table->text('pdf')->nullable(true);
        // $table->integer('organization_structure_id')->nullable(false);
        // $table->integer('position_id')->nullable(false);
        // $table->text('job_desp')->nullable(true);
        // $table->text('desp')->nullable(true);
        // $table->integer('created_by')->nullable(true);
        // $table->integer('updated_by')->nullable(true);
        // $table->integer('deleted_by')->nullable(true);

        $organizationStructurePosition->position;
        $organizationStructurePosition->permissions;
        $organizationStructurePosition->organizationStructure;
        $rootPermission = \App\Models\Permission::where('pid',0)->where('tpid','0')->first();
        return response()->json([
            'ok' => true ,
            'permission' => $rootPermission == null ? null : $rootPermission ,
            'permissions' => $rootPermission == null ? [] : \App\Models\Permission::where('tpid','like', $rootPermission->tpid .':'. $rootPermission->id . '%' )->get()  ,
            'record' => $organizationStructurePosition ,
            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('permissions')->with('position')->with('organizationStructure')->get() ,
            'message' => 'រួចរាល់។'
        ],200);
    }
    public function readPosition(Request $request){
        $organizationStructurePosition = intval( $request->organization_structure_id ) ? OrganizationStructurePosition::find( $request->organization_structure_id ) : null ;
        if( $organizationStructurePosition == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានអង្គភាពនេះឡើយ។'
            ],500);
        }
        $organizationStructurePosition->permissions;
        $organizationStructurePosition->position;
        $organizationStructurePosition->organizationStructure;
        return response()->json([
            'ok' => true ,
            'record' => $organizationStructurePosition ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    public function addPosition(Request $request){
        $user = \Auth::user() ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អត្តសញ្ញាណ។'
            ],403);
        }
        // Get Organization Structure
        $organizationStructure = intval( $request->organization_structure_id ) > 0 ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
        if( $organizationStructure == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីរចនាសម្ព័ន្ធអង្គភាព។'
            ],500);
        }
        // Get child
        $position = isset( $request->position_id ) && intval( $request->position_id ) > 0 ? Position::find( $request->position_id ) : null ;
        if( $position == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'តួនាទី នេះមិនមានឡើយ។'
            ],500);
        }
        // Get parent
        $parentNode = isset( $request->pid ) && intval( $request->pid ) > 0 ? OrganizationStructurePosition::find( $request->pid ) : null ;
        $organizationStructurePosition = null ;
        if( $parentNode == null ){
            // This is root
            $organizationStructurePosition = OrganizationStructurePosition::where('position_id',$position->id)
                ->where('organization_structure_id', $organizationStructure->id)
                ->where(function($query){
                    $query->whereNull('pid')
                    ->orWhere('pid',0);
                })->first();
            if( $organizationStructurePosition == null ){
                $organizationStructurePosition = OrganizationStructurePosition::create([
                    'organization_structure_id' => $organizationStructure->id ,
                    'position_id' => $position->id ,
                    'pid' => 0 ,
                    'tpid' => $parentNode == null > 0 ? '0' : $parentNode->tpid .':'. $parentNode->id ,
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
                $organizationStructurePosition->position;
                $organizationStructurePosition->permissions;
                $organizationStructurePosition->organizationStructure;
                $organizationStructure->organization ;
                return response()->json([
                    'ok' => true ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                ],200);
            }else{
                $organizationStructure->organization ;
                $organizationStructurePosition->position;
                $organizationStructurePosition->position;
                $organizationStructurePosition->organizationStructure;
                return response()->json([
                    'ok' => false ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'មានរួចហើយ'
                ],200);
            }
        }else{
            $organizationStructure = intval( $request->organization_structure_id ) > 0 ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
            if( $organizationStructure == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'សូមបញ្ជាក់អំពីរចនាសម្ព័ន្ធអង្គភាព។'
                ],500);
            }

            // Get child
            $position = isset( $request->position_id ) && intval( $request->position_id ) > 0 ? Position::find( $request->position_id ) : null ;
            if( $position == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'តួនាទី នេះមិនមានឡើយ។'
                ],500);
            }

            $organizationStructurePosition = OrganizationStructurePosition::where('position_id',$position->id)
                ->where('organization_structure_id', $organizationStructure->id)
                ->where(function($query){
                    $query->whereNull('pid')
                    ->orWhere('pid',0);
                })->first();

            if( $organizationStructurePosition == null ){
                // This is child
                // $organizationStructure = $organization->structure()->create([
                $organizationStructurePosition = OrganizationStructurePosition::create([
                    'organization_structure_id' => $organizationStructure->id ,
                    'position_id' => $position->id ,
                    'pid' => $parentNode->id ,
                    'tpid' => $parentNode->tpid != null && strlen( $parentNode->tpid ) > 0 ? $parentNode->tpid .':'. $parentNode->id : '0:'.$parentNode->id ,
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
                // Update child list of parentnode
                $cids = strlen( trim($parentNode->cids) ) > 0 ? explode( ',' , trim($parentNode->cids) ) : [] ;
                $cids[] = $organizationStructurePosition->id ;
                $parentNode->update([
                    'cids' => count( $cids ) > 1 ? implode(',',$cids) : $cids[0]
                ]);
                $organizationStructure->organization;
                $organizationStructurePosition->position;
                $organizationStructurePosition->permissions;
                $organizationStructurePosition->organizationStructure;
                return response()->json([
                    'ok' => true ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'បានបញ្ចូលកូនអង្គភាពរួចហើយ។'
                ],200);
            }else{
                $organizationStructure->organization;
                $organizationStructurePosition->position;
                $organizationStructurePosition->permissions;
                $organizationStructurePosition->organizationStructure;
                return response()->json([
                    'ok' => false ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'មានរួចហើយ'
                ],200);
            }
        }
    }
    public function deletePositionNode(Request $request){
        $user = \Auth::user() ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អត្តសញ្ញាណ។'
            ],403);
        }
        $organizationStructurePosition = intval( $request->id ) > 0 ? OrganizationStructurePosition::find( $request->id ) : null ;
        if( $organizationStructurePosition != null ){
            $cids = strlen( trim($organizationStructurePosition->cids) ) > 0 ? explode( ',' , trim($organizationStructurePosition->cids) ) : [] ;
            if( count( $cids ) > 0 ){
                foreach( $cids as $index => $id ){
                    if( ( $organizationStructurePosition == OrganizationStructurePosition::find( $id ) ) != null ){
                        return response()->json([
                            'ok' => false ,
                            'record' => $organizationStructurePosition ,
                            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('organization')->get() ,
                            'message' => 'មិនអាចលុបបានដោយសារមានអង្គភាពពីក្រោមបង្គាប់ ' . count( $cids ). '។'
                        ],500);
                    }
                }
            }
            $organizationStructurePosition->update([ 'deleted_by' => $user->id ]);
            $organizationStructurePosition->position;
            $organizationStructurePosition->permissions;
            $organizationStructurePosition->organizationStructure;
            return response()->json([
                'record' => $organizationStructurePosition ,
                'ok' => $organizationStructurePosition->delete() ,
                'message' => 'បាបលុបរួចរាល់។'
            ],200);
        }
        return response()->json([
            'ok' => true ,
            'message' => 'មិនមានអ្វីលុបឡើយ។'
        ],200);
    }
    public function positionPermissionToggle(Request $request){
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $permission = intval( $request->permission_id ) > 0 ? \App\Models\Permission::find( $request->permission_id ) : null ;
        if( $organizationStructurePosition == null || $permission == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់សិទ្ធិ និង តួនាទីដែលត្រូវទទួលបានសិទ្ធិនេះ។'
            ],500);
        }
        $organizationStructurePosition->permissions()->toggle([$permission->id]);
        $organizationStructurePosition->position;
        $organizationStructurePosition->permissions;
        $organizationStructurePosition->organizationStructure;
        $rootPermission = \App\Models\Permission::where('pid',0)->where('tpid','0')->first();
        return response()->json([
            'ok' => true ,
            'permission' => $rootPermission == null ? null : $rootPermission ,
            'permissions' => $rootPermission == null ? [] : \App\Models\Permission::where('tpid','like', $rootPermission->tpid .':'. $rootPermission->id . '%' )->get()  ,
            'record' => $organizationStructurePosition ,
            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . '%' )->with('permissions')->with('position')->with('organizationStructure')->get() ,
            'message' => 'រួចរាល់។'
        ],200);
    }
    public function getByMinistryTypeSorted(Request $request)
    {
        $queryString = [
            "search" => $request->search ? [
                'value' => $request->search,
                'fields' => ['name', 'desp']
            ] : [],
            "order" => [
                'field' => 'record_index',
                'by' => 'desc'
            ],
            "pagination" => [
                'perPage' => $request->perPage ?? 100,
                'page' => $request->page ?? 1
            ]
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->fields, false, $this->renameFields);

        $builder = $crud->getListBuilder();

        $builder->where('ownership_type','like', "%#ministry%");

        $responseData = $crud->pagination(true, $builder);

        $responseData['ok'] = true;
        $responseData['message'] = __("crud.read.success");

        return response()->json($responseData);
    }

    public function getByGeneralDepartmentSorted(Request $request)
    {
        $queryString = [
            "search" => $request->search ? [
                'value' => $request->search,
                'fields' => ['name', 'desp']
            ] : [],
            "order" => [
                'field' => 'record_index',
                'by' => 'desc'
            ],
            "pagination" => [
                'perPage' => $request->perPage ?? 100,
                'page' => $request->page ?? 1
            ]
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->fields, false, $this->renameFields);

        $builder = $crud->getListBuilder();

        $builder->where('ownership_type','like', "%#GeneralDepartment%");

        $responseData = $crud->pagination(true, $builder);

        $responseData['ok'] = true;
        $responseData['message'] = __("crud.read.success");

        return response()->json($responseData);
    }
    public function getByDepartmentSorted(Request $request)
    {
        $queryString = [
            "search" => $request->search ? [
                'value' => $request->search,
                'fields' => ['name', 'desp']
            ] : [],
            "order" => [
                'field' => 'record_index',
                'by' => 'desc'
            ],
            "pagination" => [
                'perPage' => $request->perPage ?? 100,
                'page' => $request->page ?? 1
            ]
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->fields, false, $this->renameFields);

        $builder = $crud->getListBuilder();

        $builder->where('ownership_type','like', "%#Department%");

        $responseData = $crud->pagination(true, $builder);

        $responseData['ok'] = true;
        $responseData['message'] = __("crud.read.success");

        return response()->json($responseData);
    }
    public function getByDivisionSorted(Request $request)
    {
        $queryString = [
            "search" => $request->search ? [
                'value' => $request->search,
                'fields' => ['name', 'desp']
            ] : [],
            "order" => [
                'field' => 'record_index',
                'by' => 'desc'
            ],
            "pagination" => [
                'perPage' => $request->perPage ?? 100,
                'page' => $request->page ?? 1
            ]
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->fields, false, $this->renameFields);

        $builder = $crud->getListBuilder();

        $builder->where('ownership_type','like', "%#Division%");

        $responseData = $crud->pagination(true, $builder);

        $responseData['ok'] = true;
        $responseData['message'] = __("crud.read.success");

        return response()->json($responseData);
    }

    public function getSecretariatOfStateOffice(Request $request){

        $organizations = RecordModel::secretariatOfStateOffice()->get();

        return $organizations;
    }
    public function getConsultationGroup(Request $request){

        $organizations = RecordModel::consultationgroup()->get();

        return $organizations;
    }
    public function getAuthority(Request $request){

        $organizations = RecordModel::authority()->get();

        return $organizations;
    }
    public function getCouncil(Request $request){

        $organizations = RecordModel::council()->get();

        return $organizations;
    }
    public function getMinistry(Request $request){

        $organizations = RecordModel::ministry()->get();

        return $organizations;
    }
    public function getGeneralDepartment(Request $request){

        $organizations = RecordModel::general_department()->get();

        return $organizations;
    }
    public function getDepartment(Request $request){

        $organizations = RecordModel::department()->get();

        return $organizations;
    }
    public function getTeamwork(Request $request){

        $organizations = RecordModel::teamwork()->get();

        return $organizations;
    }

    /**
     * Get Hierarchy Sort Desc from Each Industries
     */
    public function getOrganizationsOrderByIndustries(Request $request){
        $organization = RecordModel::getAllOrganizationTypes();
        return  $organization;
    }
    public function getHierarchyFromConsultationGroup(Request $request){
        $organizations = RecordModel::getHierarchyFromConsultationGroup();
        return $organizations;
    }
    public function getHierarchyFromAuthority(Request $request){
        $organizations = RecordModel::getHierarchyFromAuthority();
        return $organizations;
    }
    public function getHierarchyFromCouncil(Request $request){
        $organizations = RecordModel::getHierarchyFromCouncil();
        return $organizations;
    }
    public function getHierarchyFromMinistry(Request $request){
        $organizations = RecordModel::getHierarchyFromMinistry();
        return $organizations;
    }
    public function getHierarchyFromGeneralDepartment(Request $request){
        $organizations = RecordModel::getHierarchyFromGeneralDepartment();
        return $organizations;
    }
    public function getHierarchyFromDepartment(Request $request){
        $organizations = RecordModel::getHierarchyFromDepartment();
        return $organizations;
    }
    public function getHierarchyFromDivision(Request $request){
        $organizations = RecordModel::getHierarchyFromDivision();
        return $organizations;
    }


}
