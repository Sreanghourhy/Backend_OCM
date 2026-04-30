<?php

namespace App\Http\Controllers\Api\Admin\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\Position\Position;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Organization as RecordModel;
use App\Models\Organization\OrganizationStructure;
use App\Models\Organization\OrganizationStructurePosition;


class OrganizationController extends Controller
{
    private $model = null ;
    private $fields = [ 'id','name','desp' , 'pid' , 'tpid' , 'prefix', 'record_index' , 'active' ] ;
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

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , false , [
            'totalChilds' => function($record){
                // return $record->totalChildNodesOfAllLevels();
                return 0 ;
            },
            'parentId' => function($record){
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
        $root->totalChilds = $root->totalChildNodesOfAllLevels();

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
                    [
                        'field' => 'tpid' ,
                        'value' => ( intval( $root->pid ) > 0 ? '%' . $root->pid.":" : '' ) . $root->id . "%"
                    ],
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

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , false , [
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
        $root->parentId = null ;
        $root->staffs;

        $responseData = $crud->pagination(true , $builder );
        $responseData['records'] = $responseData['records']->prepend( $root );
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
            'tpid' => $parentNode != null ? ( $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id . ':' : "0:".$parentNode->id ). ':' : "0". ':',
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
            'tpid' => $parentNode != null ? ( $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id. ':' : "0:".$parentNode->id. ':' ) : "0". ':',
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
    // public function getStructure(Request $request){
    //     $organizationStructure = intval( $request->organization_structure_id ) ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
    //     if( $organizationStructure == null ){
    //         return response()->json([
    //             'ok' => true ,
    //             'records' => [] ,
    //             'message' => 'មិនមានបញ្ជាក់អង្គភាពមេ។'
    //         ],200);
    //     }
    //     $organizationStructure->organization;
    //     $organizationStructure->positions = OrganizationStructurePosition::where('organization_structure_id', $organizationStructure->id)->get();
    //     return response()->json([
    //         'ok' => true ,
    //         'record' => $organizationStructure ,
    //         'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . ':' . '%' )->with('organization')->get()->map(function($organizationStructure){
    //             $organizationStructure->positions = OrganizationStructurePosition::where('organization_structure_id', $organizationStructure->id)->get();
    //             return $organizationStructure;
    //         }) ,
    //         'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
    //     ],200);
    // }

    public function getStructure(Request $request){
        $organizationStructure = \App\Models\Organization\OrganizationStructure::whereNull('pid')
        ->orWhere('pid','0')
        ->orWhere('pid',0)
        ->first();

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
            'sql' => $organizationStructure['query'] ,
            'record' => $organizationStructure['record'] ,
            'records' => $organizationStructure['records'] ,
            'message' => 'រួចរាល់។'

        ],200);
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
            // $tpid = explode( ':' , $organizationStructure->tpid );
            // if(
            //     count( $tpid ) > 1 &&
            //     (
            //         $root = (
            //             ( $rootId = intval( $tpid[1] ) ) > 0
            //                 ? OrganizationStructure::find( $rootId )
            //                 : null
            //         )
            //     )  != null
            // ){
            //     // return with the existing OrganizationStructure
            //     $root->organization;
            //     return response()->json([
            //         'ok' => true ,
            //         'record' => $root ,
            //         'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . '%' )->with('organization')->get() ,
            //         'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
            //     ],200);
            // }else{
                $organizationStructure->organization;
                return response()->json([
                    'ok' => true ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
                ],200);
            // }
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
                    'tpid' => $parentNode == null > 0 ? '0'. ':' : $parentNode->tpid .':'. $parentNode->id . ':',
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
                $organizationStructure->updateNumberOfChilds();
                // $organizationStructure->organization;
                // return response()->json([
                //     'ok' => true ,
                //     'record' => $organizationStructure ,
                //     'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id )->with('organization')->get() ,
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
                    $root->updateNumberOfChilds();
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $root ,
                        'records' => OrganizationStructure::where('tpid','like', $root->tpid .':'. $root->id . ':' . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                    ],200);
                }else{
                    $organizationStructure->organization;
                    return response()->json([
                        'ok' => true ,
                        'tpid' => $tpid ,
                        'record' => $organizationStructure ,
                        'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . ':' . '%' )->with('organization')->get() ,
                        'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                    ],200);
                }

            }else{
                $organizationStructure->organization;
                return response()->json([
                    'ok' => false ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . ':' . '%' )->with('organization')->get() ,
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
                    'tpid' => $parentNode->tpid != null && strlen( $parentNode->tpid ) > 0 ? $parentNode->tpid .':'. $parentNode->id. ':' : '0:'.$parentNode->id . ':',
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
                $parentNode->updateNumberOfChilds();

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
    /**
     * Move organization structure
     */
    public function moveStructure(Request $request){
        $user = \Auth::user() ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អត្តសញ្ញាណ។'
            ],403);
        }
        // Get parent
        $organization = isset( $request->parent_organization_id ) && intval( $request->parent_organization_id ) > 0 ? RecordModel::find( $request->parent_organization_id ): null ;
        $parentNode = null ;
        if( $organization->structure->count() > 1 ){
            return response()->json([
                'ok' => false ,
                'parent' => $organization->structure ,
                'message' => 'អង្គភាពមេនេះត្រូវបានប្រើប្រាស់នៅកន្លែងផ្សេងៗគ្នា។'
            ],500);
        }
        else if( $organization->structure->count() == 1 ){
            $parentNode = $organization->structure->first();
        }

        // Get child
        $childNode = isset( $request->child_organization_id ) && intval( $request->child_organization_id ) > 0 ? OrganizationStructure::find( $request->child_organization_id ) : null ;
        if( $childNode == null || $parentNode == null ){
            return response()->json([
                'ok' => false ,
                'child' => $childNode ,
                'parent' => $parentNode ,
                'message' => 'សូមបញ្ជាក់អង្គភាពមេ និង អង្គភាពចំណុះ។'
            ],500);
        }

        $childNode->update([
            'pid' => $parentNode->id ,
            // Update tpid because the last id of the tpid is the id of the previous parent node and we need to change it to the new parent node
            'tpid' => $parentNode->tpid . $parentNode->id . ':',
            'updated_by' => $user->id
        ] );
        $parentNode->organization;
        $childNode->organization;
        $parent->updateNumberOfChilds();
        $childNode->updateNumberOfChilds();
        return response()->json([
            'ok' => true ,
            'record' => $parentNode ,
            'records' => OrganizationStructure::where('tpid','like', $parentNode->tpid .':'. $parentNode->id . ':' . '%' )->with('organization')->get() ,
            'message' => 'អង្គភាពនេះបានប្រើរួចហើយ'
        ],200);
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
                            'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . ':' )->with('organization')->get() ,
                            'message' => 'មិនអាចលុបបានដោយសារមានអង្គភាពពីក្រោមបង្គាប់ ' . count( $cids ). '។'
                        ],500);
                    }
                }
            }
            $organizationStructure->update([ 'deleted_by' => $user->id ]);
            $organizationStructure->organization;
            $organizationStructure->updateNumberOfChilds();
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

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , [] , [
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

        $organizationStructurePosition->position;
        $organizationStructurePosition->permissions;
        $organizationStructurePosition->organizationStructure;
        $rootPermission = \App\Models\Permission::where('pid',0)->where('tpid','0:')->first();
        return response()->json([
            'ok' => true ,
            'permission' => $rootPermission == null ? null : $rootPermission ,
            'permissions' => $rootPermission == null ? [] : \App\Models\Permission::where('tpid','like', $rootPermission->tpid .':'. $rootPermission->id . ':' . '%' )->get()  ,
            'record' => $organizationStructurePosition ,
            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )
                ->where('organization_structure_id' , $organizationStructurePosition->organizationStructure->id )
                ->with('permissions')->with('position')->with('organizationStructure')->get()
                ,
            'combination' => $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id  ,
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
                    'tpid' => $parentNode == null > 0 ? '0:' : $parentNode->tpid .':'. $parentNode->id . ':',
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
                $organizationStructurePosition->updateNumberOfPositions();
                return response()->json([
                    'ok' => true ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                ],200);
            }else{
                $organizationStructure->organization ;
                $organizationStructurePosition->position;
                $organizationStructurePosition->position;
                $organizationStructurePosition->organizationStructure;
                $organizationStructurePosition->updateNumberOfPositions();
                return response()->json([
                    'ok' => false ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
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
                    'tpid' => $parentNode->tpid != null && strlen( $parentNode->tpid ) > 0 ? $parentNode->tpid .':'. $parentNode->id . ':' : '0:'.$parentNode->id . ':' ,
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
                $organizationStructurePosition->updateNumberOfPositions();
                return response()->json([
                    'ok' => true ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
                    'message' => 'បានបញ្ចូលកូនអង្គភាពរួចហើយ។'
                ],200);
            }else{
                $organizationStructure->organization;
                $organizationStructurePosition->position;
                $organizationStructurePosition->permissions;
                $organizationStructurePosition->organizationStructure;
                $organizationStructurePosition->updateNumberOfPositions();
                return response()->json([
                    'ok' => false ,
                    'organizationStructure' => $organizationStructure ,
                    'record' => $organizationStructurePosition ,
                    'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('position')->with('organizationStructure')->with('permissions')->get() ,
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
                            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('organization')->get() ,
                            'message' => 'មិនអាចលុបបានដោយសារមានអង្គភាពពីក្រោមបង្គាប់ ' . count( $cids ). '។'
                        ],500);
                    }
                }
            }
            $organizationStructurePosition->update([ 'deleted_by' => $user->id ]);
            $organizationStructurePosition->position;
            $organizationStructurePosition->permissions;
            $organizationStructurePosition->organizationStructure;
            $organizationStructurePosition->updateNumberOfPositions();
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
        $organizationStructurePosition->updateNumberOfPositions();
        $rootPermission = \App\Models\Permission::where('pid',0)->where('tpid','0')->first();
        return response()->json([
            'ok' => true ,
            'permission' => $rootPermission == null ? null : $rootPermission ,
            'permissions' => $rootPermission == null ? [] : \App\Models\Permission::where('tpid','like', $rootPermission->tpid .':'. $rootPermission->id . ':' . '%' )->get()  ,
            'record' => $organizationStructurePosition ,
            'records' => OrganizationStructurePosition::where('tpid','like', $organizationStructurePosition->tpid .':'. $organizationStructurePosition->id . ':' . '%' )->with('permissions')->with('position')->with('organizationStructure')->get() ,
            'message' => 'រួចរាល់។'
        ],200);
    }
    public function organizationStructureFlatTree(Request $request){
        $organizationStructure = \App\Models\Organization\OrganizationStructure::whereNull('pid')->orWhere('pid',0)->first();
        // return response()->json( [
        //     'data' => $organizationStructure->getStructure()
        // ],200 ) ;
        return response()->json( [
            'data' => $organizationStructure->getStructure()
        ],200 ) ;
    }
    public function organizationStgructureNestedTree(Request $request){
        $sqlNested = "
        -- Nested Tree Structure as JSON
        WITH RECURSIVE org_tree AS (
            -- Anchor: Get all root nodes
            SELECT
                os.id,
                os.name,
                os.name_en,
                os.organization_id,
                o.name as organization_name,
                os.pid,
                os.tpid,
                os.cids,
                os.structure_type_id,
                st.name_kh as structure_type_name,
                st.category as structure_category,
                1 as level,
                os.id as root_id,
                jsonb_build_object(
                    'id', os.id,
                    'name', os.name,
                    'name_en', os.name_en,
                    'level', 1,
                    'organization_id', os.organization_id,
                    'organization_name', o.name,
                    'structure_type_id', os.structure_type_id,
                    'structure_type_name', st.name_kh,
                    'structure_category', st.category,
                    'children', jsonb_build_array()
                ) as node_json
            FROM organization_structures os
            LEFT JOIN organizations o ON os.organization_id = o.id AND o.deleted_at IS NULL
            LEFT JOIN structure_types st ON os.structure_type_id = st.id
            WHERE (os.pid IS NULL OR os.pid = 0)
            AND os.deleted_at IS NULL

            UNION ALL

            -- Recursive: Build children
            SELECT
                os.id,
                os.name,
                os.name_en,
                os.organization_id,
                o.name,
                os.pid,
                os.tpid,
                os.cids,
                os.structure_type_id,
                st.name_kh,
                st.category,
                ot.level + 1,
                ot.root_id,
                jsonb_build_object(
                    'id', os.id,
                    'name', os.name,
                    'name_en', os.name_en,
                    'level', ot.level + 1,
                    'organization_id', os.organization_id,
                    'organization_name', o.name,
                    'structure_type_id', os.structure_type_id,
                    'structure_type_name', st.name_kh,
                    'structure_category', st.category,
                    'parent_id', os.pid,
                    'children', jsonb_build_array()
                ) as node_json
            FROM organization_structures os
            INNER JOIN org_tree ot ON os.pid = ot.id
            LEFT JOIN organizations o ON os.organization_id = o.id AND o.deleted_at IS NULL
            LEFT JOIN structure_types st ON os.structure_type_id = st.id
            WHERE os.deleted_at IS NULL
        )
        -- Aggregate children into parents
        SELECT
            jsonb_agg(
                jsonb_set(
                    node_json,
                    '{children}',
                    COALESCE((
                        SELECT jsonb_agg(child.node_json)
                        FROM org_tree child
                        WHERE child.pid = parent.id
                    ), '[]'::jsonb)
                )
                ORDER BY parent.level, parent.name
            ) as tree
        FROM org_tree parent
        WHERE parent.pid IS NULL OR parent.pid = 0;" ;

        $sqlNestedCountOfficer = "
            -- Nested Tree Structure WITH Officer Counts
            WITH RECURSIVE org_tree AS (
                -- Anchor: Get all root nodes with officer counts
                SELECT
                    os.id,
                    os.name,
                    os.organization_id,
                    o.name as organization_name,
                    os.pid,
                    os.tpid,
                    os.cids,
                    1 as level,
                    os.id as root_id,
                    -- Get total officers in this structure (with date casting)
                    COALESCE((
                        SELECT COUNT(DISTINCT oj.officer_id)
                        FROM organization_structure_positions osp
                        LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                            AND (oj.end IS NULL OR oj.end = '' OR oj.end::date >= CURRENT_DATE)
                            AND oj.deleted_at IS NULL
                        WHERE osp.organization_structure_id = os.id
                        AND osp.deleted_at IS NULL
                    ), 0) as total_officers,
                    -- Get total positions
                    COALESCE((
                        SELECT SUM(osp.total_jobs)
                        FROM organization_structure_positions osp
                        WHERE osp.organization_structure_id = os.id
                        AND osp.deleted_at IS NULL
                    ), 0) as total_positions,
                    jsonb_build_object(
                        'id', os.id,
                        'name', os.name,
                        'level', 1,
                        'organization_id', os.organization_id,
                        'organization_name', o.name,
                        'total_officers', COALESCE((
                            SELECT COUNT(DISTINCT oj.officer_id)
                            FROM organization_structure_positions osp
                            LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                                AND (oj.end IS NULL OR oj.end = '' OR oj.end::date >= CURRENT_DATE)
                                AND oj.deleted_at IS NULL
                            WHERE osp.organization_structure_id = os.id
                            AND osp.deleted_at IS NULL
                        ), 0),
                        'total_positions', COALESCE((
                            SELECT SUM(osp.total_jobs)
                            FROM organization_structure_positions osp
                            WHERE osp.organization_structure_id = os.id
                            AND osp.deleted_at IS NULL
                        ), 0),
                        'children', jsonb_build_array()
                    ) as node_json
                FROM organization_structures os
                LEFT JOIN organizations o ON os.organization_id = o.id AND o.deleted_at IS NULL
                WHERE (os.pid IS NULL OR os.pid = 0)
                AND os.deleted_at IS NULL

                UNION ALL

                -- Recursive: Get children with counts
                SELECT
                    os.id,
                    os.name,
                    os.organization_id,
                    o.name,
                    os.pid,
                    os.tpid,
                    os.cids,
                    ot.level + 1,
                    ot.root_id,
                    COALESCE((
                        SELECT COUNT(DISTINCT oj.officer_id)
                        FROM organization_structure_positions osp
                        LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                            AND (oj.end IS NULL OR oj.end = '' OR oj.end::date >= CURRENT_DATE)
                            AND oj.deleted_at IS NULL
                        WHERE osp.organization_structure_id = os.id
                        AND osp.deleted_at IS NULL
                    ), 0),
                    COALESCE((
                        SELECT SUM(osp.total_jobs)
                        FROM organization_structure_positions osp
                        WHERE osp.organization_structure_id = os.id
                        AND osp.deleted_at IS NULL
                    ), 0),
                    jsonb_build_object(
                        'id', os.id,
                        'name', os.name,
                        'level', ot.level + 1,
                        'organization_id', os.organization_id,
                        'organization_name', o.name,
                        'parent_id', os.pid,
                        'total_officers', COALESCE((
                            SELECT COUNT(DISTINCT oj.officer_id)
                            FROM organization_structure_positions osp
                            LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                                AND (oj.end IS NULL OR oj.end = '' OR oj.end::date >= CURRENT_DATE)
                                AND oj.deleted_at IS NULL
                            WHERE osp.organization_structure_id = os.id
                            AND osp.deleted_at IS NULL
                        ), 0),
                        'total_positions', COALESCE((
                            SELECT SUM(osp.total_jobs)
                            FROM organization_structure_positions osp
                            WHERE osp.organization_structure_id = os.id
                            AND osp.deleted_at IS NULL
                        ), 0),
                        'children', jsonb_build_array()
                    ) as node_json
                FROM organization_structures os
                INNER JOIN org_tree ot ON os.pid = ot.id
                LEFT JOIN organizations o ON os.organization_id = o.id AND o.deleted_at IS NULL
                WHERE os.deleted_at IS NULL
            )
            -- Aggregate children into parents
            SELECT
                jsonb_agg(
                    jsonb_set(
                        node_json,
                        '{children}',
                        COALESCE((
                            SELECT jsonb_agg(child.node_json ORDER BY child.name)
                            FROM org_tree child
                            WHERE child.pid = parent.id
                        ), '[]'::jsonb)
                    )
                    ORDER BY parent.name
                ) as tree
            FROM org_tree parent
            WHERE parent.pid IS NULL OR parent.pid = 0;" ;

        $sql = intval( $request->is_count_officer ) > 0
            ? $sqlNested
            : $sqlNestedCountOfficer;

        $result = DB::select($sql);
        return json_decode($result[0]->tree ?? '[]', true);
    }
}
