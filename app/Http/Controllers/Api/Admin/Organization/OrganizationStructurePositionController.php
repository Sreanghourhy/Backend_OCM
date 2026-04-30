<?php

namespace App\Http\Controllers\Api\Admin\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationStructure;
use App\Models\Organization\OrganizationStructurePosition as RecordModel;


class OrganizationStructurePositionController extends Controller
{
    private $fields = [ 'id','organization_structure_id','cids' , 'pid' , 'tpid' , 'desp', 'image' , 'pdf' , 'position_id' , 'job_desp' ] ;
    private $renameFields = [
        'pid' => 'parentId'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? intval( $request->perPage ) : 1000 ;
        $page = isset( $request->page ) && $request->page !== "" ? intval( $request->page ) : 1 ;
        $organizationStructure = isset( $request->organization_structure_id ) && intval( $request->organization_structure_id ) > 0 ? OrganizationStructure::find( $request->organization_structure_id ) : null ; 

        $queryString = [
            "where" => [
                'default' => [
                    $organizationStructure != null && $organizationStructure->id > 0 
                    ? [
                        'field' => '$organization_structure_id' ,
                        'value' => $organizationStructure->id
                    ] : [] ,
                    [
                        'deleted_at' => NULL
                    ]
                ],
                
                // 'in' => [] ,

                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                // 'like' => [
                //     $root != null 
                //     ? [
                //         'field' => 'tpid' ,
                //         'value' => ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%"
                //     ]
                //     : [],
                //     // [
                //     //     'field' => 'year' ,
                //     //     'value' => $date === false ? "" : $date
                //     // ]
                // ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'job_desp' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'job_desp' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields );

        $crud->setRelationshipFunctions([
            'organizationStructure' => [
                'id' , 'organization_id' , 'pid' , 'tpid' , 'cids' , 'desc' , 'image' , 'pdf' , 'active' ,
                'organization' => [ 'id' , 'name' , 'desp' , 'tpid' , 'pid' , 'cids' , 'image' , 'prefix' ]
            ],
            'position' => [
                'id' , 'name' , 'desp' , 'tpid' , 'pid' , 'cids' , 'image' , 'prefix'
            ],
            'officerJobs' => [ 
                'id' , 'organization_structure_position_id' , 'officer_id' , 'countesy_id' , 'start' , 'end' , 'created_at' , 'updated_at' ,
                'officer' => [ 
                    'id' , 'code' , 'official_date' , 'unofficial_date' , 'public_key' , 'user_id' ,'people_id' , 'email' , 'phone' ,
                    'people' => [ 'id' , 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' ] 
                ] ,
                'countesy' => [ 'id' , 'name' ]
            ],
            'permissions' => [ 'id' , 'name' , 'guard_name' , 'code' , 'tag' , 'pid' , 'tpid' ]
        ]);

        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true , $builder );
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
        
        if( $parentNode == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្ថាប័នមេ នេះមិនមានឡើយ។'
            ],403);
        }
        
        // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
        $record = RecordModel::create([
            'name' => $request->name,
            'desp' => $request->desp ,
            'pid' => $parentNode->id ,
            'tpid' => $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id . ':': "0:".$parentNode->id . ':',
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
        $parentNode = intval( $request->pid ) > 0 ? RecordModel::find( $request->pid ) : null ;
        if( $parentNode == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្ថាប័នមេនេះមិនមានឡើយ។'
            ],403);
        }
        $record = isset( $request->id ) && intval( $request->id ) > 0 ? RecordModel::find($request->id) : null ;
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្ថាប័នមនេះមិនមានឡើយ។'
            ],403);
        }
        $updateData = [
            'name' => $request->name ,
            'desp' => $request->desp ,
            'pid' => $parentNode->id ,
            'tpid' => $parentNode->tpid != null && $parentNode->tpid != "" ? $parentNode->tpid .':'. $parentNode->id. ':' : $parentNode->id . ':',
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
        $organizationStructure = intval( $request->organization_structure_id ) ? OrganizationStructure::find( $request->organization_structure_id ) : null ;
        if( $organizationStructure == null ){
            return response()->json([
                'ok' => true ,
                'records' => [] ,
                'message' => 'មិនមានបញ្ជាក់អង្គភាពមេ។' 
            ],200);
        }
        $organizationStructure->organization;
        return response()->json([
            'ok' => true ,
            'record' => $organizationStructure ,
            'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
            'message' => 'មិនមានបញ្ជាក់អង្គភាពមេ។' 
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
     * Create child
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
                    'tpid' => $parentNode == null > 0 ? '0' . ':': $parentNode->tpid .':'. $parentNode->id . ':',
                    'desp' => '' ,
                    'image' => '' ,
                    'pdf' => '' ,
                    'active' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') , 
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
                $organizationStructure->organization;
                return response()->json([
                    'ok' => true ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'បានបញ្ចូលមេអង្គភាពរួចហើយ។'
                ],200);
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
                    'tpid' => $parentNode->tpid != null && strlen( $parentNode->tpid ) > 0 ? $parentNode->tpid .':'. $parentNode->id . ':': '0:'.$parentNode->id . ':',
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
                $organizationStructure->organization;
                return response()->json([
                    'ok' => true ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'បានបញ្ចូលកូនអង្គភាពរួចហើយ។'
                ],200);
            }else{
                return response()->json([
                    'ok' => false ,
                    'record' => $organizationStructure ,
                    'records' => OrganizationStructure::where('tpid','like', $organizationStructure->tpid .':'. $organizationStructure->id . '%' )->with('organization')->get() ,
                    'message' => 'មានរួចហើយ'
                ],200);
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
                $organizationStructure->organization;
                return response()->json([
                    'ok' => false , 
                    'record' => $organizationStructure ,
                    'message' => 'មិនអាចលុបបានដោយសារមានអង្គភាពពីក្រោមបង្គាប់ ' . count( $cids ). '។' 
                ],500);
            }else{
                $organizationStructure->update([ 'deleted_by' => $user->id ]);
                $organizationStructure->organization;
                return response()->json([
                    'record' => $organizationStructure ,
                    'ok' => $organizationStructure->delete() , 
                    'message' => 'បាបលុបរួចរាល់។'
                ],200);
            }
        }
        return response()->json([
            'ok' => true , 
            'message' => 'មិនមានអ្វីលុបឡើយ។'
        ],200);
    }
}