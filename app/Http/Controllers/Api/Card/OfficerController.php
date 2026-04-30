<?php

namespace App\Http\Controllers\Api\Card;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\MobilePasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\Officer\Officer as RecordModel ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use \Gumlet\ImageResize;


class OfficerController extends Controller
{
    private $selectFields = [
        'id',
        'public_key' ,
        'code' ,
        'people_id' ,
        'official_date' ,
        'unofficial_date' ,
        'image' ,
        'leader' ,
        'organization_id' ,
        'position_id' ,
        'rank_id' ,
        'user_id' ,
        'countesy_id' ,
        'email' ,
        'phone' , 
        'passport' 
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && strlen( $request->search ) > 0 ? $request->search : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && intval( $request->page ) > 0 ? $request->page : 1 ;
        
        $positions = isset( $request->positions ) ? explode(',',$request->positions) : false ;
        if( is_array( $positions ) && !empty( $positions ) ){
            $positions = array_filter( $positions, function($position){
                return intval( $position ) > 0 ;
            } );
        }

        $organizations = isset( $request->organizations ) ? explode(',',$request->organizations) : false ;
        if( is_array( $organizations ) && !empty( $organizations ) ){
            $organizations = array_filter( $organizations , function($organization){
                return intval( $organization ) > 0 ;
            } );
        }

        $officerIds = isset( $request->ids ) ? explode(',',$request->ids) : false ;
        if( is_array( $officerIds ) && !empty( $officerIds ) ){
            $officerIds = array_filter( $officerIds , function($officerIds){
                return intval( $officerIds ) > 0 ;
            } );
        }

        // return response()->json([
        //     'positions' => $positions ,
        //     'organizations' => $organizations ,
        //     'peopleIds' => $peopleIds ,
        // ],200);
        
        $queryString = [
            "where" => [
            //     'default' => [
            //         [
            //             'field' => 'type_id' ,
            //             'value' => $type === false ? "" : $type
            //         ]
            //     ],
                'in' => [
                    is_array( $officerIds ) && !empty( $officerIds )
                        ?   [
                            'field' => 'id' ,
                            'value' => $officerIds
                        ]
                        : []
                ] ,
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
            ] ,
            "pivots" => [
                is_array( $organizations ) && !empty( $organizations ) ?
                [
                    "relationship" => 'organization',
                    "where" => [
                        "in" => [
                            "field" => "organization_id",
                            "value" => $organizations
                        ]
                    ]
                ]
                : [] ,
                is_array( $positions ) && !empty( $positions ) ?
                [
                    "relationship" => 'position',
                    "where" => [
                        "in" => [
                            "field" => "position_id",
                            "value" => $positions
                        ]
                    ]
                ]
                : [] ,
                strlen( $search ) > 0 ?
                [
                    "relationship" => 'people',
                    "where" => [
                        "like" => [
                            "fields" => [ 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 'dob' , 'mobile_phone' , 'office_phone' , 'email' , 'nid' , 'passport' , 'address' ] ,
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
                    'code' ,
                    'date'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            'image' => function( $officer ){
                return $officer['image'] != null && \Storage::disk('public')->exists( $officer['image'] )
                ? \Storage::disk('public')->url( $officer['image'] )
                : (
                    isset( $officer['user'] ) && $officer['user']['avatar_url'] != null && \Storage::disk('public')->exists( $officer['user']['avatar_url'] )
                    ? \Storage::disk('public')->url( $officer['user']['avatar_url'] )
                    : ( 
                        isset( $officer['people'] ) && $officer['people']['image'] != null && \Storage::disk('public')->exists( $officer['people']['image'] ) 
                        ? \Storage::disk('public')->url( $officer['people']['image'] )
                        : false 
                    )
                );
            }
        ]);
        $crud->setRelationshipFunctions([
        //     /** relationship name => [ array of fields name to be selected ] */
        //     "person" => ['id','firstname' , 'lastname' , 'gender' , 'dob' , 'pob' , 'picture' ] ,
        //     "roles" => ['id','name', 'tag'] ,
            'user' => [ 
                'id' , 'username' , 'phone' , 'email' , 'avatar_url' , 'firstname' , 'lastname' ,
                'roles' => [ 'id' , 'name' ]
            ] ,
            "people" => ['id','firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 'gender' , 'dob' , 'pob' , 'image' , 'mobile_phone' , 'office_phone' , 'passport' , 'nid' , 'marry_status' , 'address' , 'email' ] ,
            'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'countesy' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        /**
         * Filter the officers to get only the officer that is not admin and super admin
         */
        $builder->whereHas('user',function($query){
            $query->whereHas('roles',function($query){
                $query->whereNot('name',['super','admin']);
            });
        });

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Read people without any conditions
     */
    public function getPeopleByIds(Request $request){
        /** Format from query string */
        $ids = isset( $request->ids ) && $request->ids !== "" && strlen( $request->ids ) > 0 ? explode( ',' , $request->ids ) : false ;
        if( !is_array( $ids ) && empty( $ids ) ){
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់។'
            ],500);
        }
        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'type_id' ,
                //         'value' => $type === false ? "" : $type
                //     ]
                // ],
                'in' => [
                    is_array( $ids )
                        ? [
                            'field' => 'id' ,
                            'value' => $ids
                        ] : []
                ] ,
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
                'perPage' => 20,
                'page' => 1
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'firstname' ,
                    'lastname' ,
                    'dob' ,
                    'mobile_phone' ,
                    'office_phone' ,
                    'email',
                    'nid'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            "countesies" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            "organizations" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            "positions" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            'user' => [ 'id' , 'username' , 'phone' , 'email' , 'avatar_url' ]
        ]);

        $builder = $crud->getListBuilder()
        ->whereNull('deleted_at')
        ->whereIn('id', $ids );

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($people){
            $people['image'] = $people['image'] != null && \Storage::disk('public')->exists( $people['image'] )
                ? \Storage::disk('public')->url( $people['image'] )
                : (
                    $people['user']['avatar_url'] != null && \Storage::disk('public')->exists( $people['user']['avatar_url'] )
                    ? \Storage::disk('public')->url( $people['user']['avatar_url'] )
                    : false
                );
            return $people;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Create an account
     */
    public function storeOfficer(Request $request){

        $validated = $request->validate([
            'code' => 'required|unique:officers|max:50',
            'organization_id' => 'required',
            'position_id' => 'required',
            'firstname' => 'required' ,
            'lastname' => 'required' ,
            'enfirstname' => 'required' ,
            'enlastname' => 'required'
        ]);

        $officer = RecordModel::where([
            'code' => $request->code ,
            'organization_id' => $request->organization_id ,
            'position_id' => $request->position_id ,
        ])->first();

        if( $officer != null ){
            $officer->orgainzation;
            $officer->position;
            $officer->countesy;
            $officer->people;
            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                'message' => 'អ្នកកំពុងព្យាយាមបញ្ចូលព័ត៌មានដែលមានរួចហើយ ' . implode( " , " , [ $officer->code , $officer->people->lastname , $officer->people->firstname ])
            ],500
            );
        }

        $peopleWhereConditions = [
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d')
        ];
        if( strlen( $request->mobile_phone ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->mobile_phone ;
        if( strlen( $request->email ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->email ;
        $people = \App\Models\People\People::where( $peopleWhereConditions )->first();
        if( $people ){
            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                'record' => $people ,
                'message' => 'គណនី '.$people->lastname . ' ' . $people->firstname .' មានក្នុងប្រព័ន្ធរួចហើយ ។' . (
                    $people->active ? " ហើយកំពុងបើកដំណើរការជាធម្មតា !" : " កំពុងត្រូវបានបិទដំណើរការ !"
                )],500
            );
        }else{

            /**
             * Create detail information of the owner of the account
             */
            $people = \App\Models\People\People::create([
                'public_key' => md5( 
                    \Carbon\Carbon::now()->format('YmdHis') . 
                    $request->enfirstname . 
                    $request->enlastname . 
                    $request->gender .
                    \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) .
                    $request->nid .
                    $request->mobile_phone .
                    $request->office_phone
                ) ,
                'firstname' => $request->firstname , 
                'lastname' => $request->lastname , 
                'enfirstname' => $request->enfirstname , 
                'enlastname' => $request->enlastname , 
                'gender' => $request->gender , 
                'dob' => \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) , 
                'nid' => $request->nid ?? '', 
                'marry_status' => $request->marry_status , 
                'mobile_phone' => $request->mobile_phone ?? '' , 
                'office_phone' => $request->office_phone ?? '' , 
                'email' => $request->email ?? '' ,
                'address' => $request->address ?? '' ,
                'pob' => $request->pob ?? ''
            ]);

            /**
             * Create officer
             */
            $officer = $people->officers()->create([
                'public_key' => md5( 
                    \Carbon\Carbon::now()->format('YmdHis') . 
                    $request->code  . 
                    $people->id .
                    $request->organization_id .
                    $request->position_id .
                    $request->countesy_id .
                    \Carbon\Carbon::parse( $request->officer_dob )->format( 'Y-m-d' )
                ),
                'code' => $request->code ,
                'people_id' => $people->id ,
                'organization_id' => $request->organization_id ,
                'position_id' => $request->position_id ,
                'countesy_id' => $request->countesy_id , 
                'date' => \Carbon\Carbon::parse( $request->officer_dob )->format( 'Y-m-d' ),
                'leader' => 0 ,
                'email' => $request->officer_email ?? 'officer'.$request->code.'@ocm.gov.kh' ,
                'phone' => $request->officer_phone ?? ( $people->email ?? '' )  ,
                'passport' => $request->officer_passport ?? ''
            ]);

            $card = $people->cards()->create([
                'number' => "OCM-". str_pad( $people->id , 4 , "0" , STR_PAD_LEFT ) ,
                'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
                'people_id' => $people->id ,
                'officer_id' => $officer->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);

            // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
            $user = $officer->user()->create([
                'firstname' => $people->firstname,
                'lastname' => $people->lastname,
                'email' => $officer->email ?? $people->email ,
                'username' => $officer->email ?? $people->email ,
                'active' => 0 ,
                'phone' => $officer->phone ?? $people->mobile_phone ,
                'password' => bcrypt( 
                    $officer->phone != null && strlen( $officer->phone ) > 0 
                        ? $officer->phone
                        : (
                            $people->mobile_phone != null && strlen( $people->mobile_phone ) > 0 
                                ? $request->mobile_phone 
                                : 'ocm@123456'
                        )
                ),
            ]);
            $user->update(['people_id' => $people->id]) ;
            $officer->update([
                'people_id' => $people->id ,
                'user_id' => $user->id ,
            ]);

            /**
             * Assign role
             */
            $backendMemberRole = \App\Models\Role::where('name','backend')->first();
            if( $backendMemberRole != null ){
                $user->assignRole( $backendMemberRole );
            }
            $user->save();

            $officer->user ;
            $officer->organization;
            $officer->countesy;
            $officer->position;
            $officer->people;
            return response()->json([
                'record' => $officer ,
                'ok' => true ,
                'message' => 'បង្កើតបានជោគជ័យ !'
            ], 200);
        }
    }
    /**
     * Create an account
     */
    public function storeNonOfficer(Request $request){

        $validated = $request->validate([
            // 'nid' => 'required|unique:people|max:50',
            'firstname' => 'required' ,
            'lastname' => 'required' ,
            'enfirstname' => 'required' ,
            'enlastname' => 'required' ,
            'organization_id' => 'required',
            'position_id' => 'required',
            'countesy_id' => 'required'
        ]);

        $peopleWhereConditions = [
            'nid' => $request->nid ,
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d')
        ];
        if( strlen( $request->mobile_phone ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->mobile_phone ;
        if( strlen( $request->email ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->email ;
        $people = \App\Models\People\People::where( $peopleWhereConditions )->first();

        if( $people != null ){
            $people->officer;
            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                    'message' => 'អ្នកកំពុងព្យាយាមបញ្ចូលព័ត៌មានដែលមានរួចហើយ ' . implode( " , " , [ ( $people->officer != null ? $people->officer->code : '' ) , $people->lastname , $people->firstname ] )
                ],
                500
            );
        }

        /**
         * Create detail information of the owner of the account
         */
        $people = \App\Models\People\People::create([
            'public_key' => md5( 
                \Carbon\Carbon::now()->format('YmdHis') . 
                $request->enfirstname . 
                $request->enlastname . 
                $request->gender .
                \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) .
                $request->nid .
                $request->mobile_phone .
                $request->office_phone
            ) ,
            'firstname' => $request->firstname , 
            'lastname' => $request->lastname , 
            'enfirstname' => $request->enfirstname , 
            'enlastname' => $request->enlastname , 
            'gender' => $request->gender , 
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d') ,
            'nid' => $request->nid , 
            'marry_status' => $request->marry_status , 
            'mobile_phone' => $request->mobile_phone ?? '' , 
            'office_phone' => $request->office_phone ,
            'email' => $request->email ?? '' ,
            'address' => $request->address ?? '' ,
            'pob' => $request->pob ?? ''
        ]);
        if( strlen( $request->email ) <= 0 ){
            $people->update([
                'email' => strtolower( $request->enlastname.'.'.$request->enfirstname.str_pad( $people->id , 3 , '0' , STR_PAD_LEFT ).'@ocm.gov.kh' )
            ]);
        }

        /**
         * Create officer
         */
        $officer = $people->officers()->create([
            'public_key' => md5( 
                \Carbon\Carbon::now()->format('YmdHis') . 
                $request->code  . 
                $people->id .
                $request->organization_id .
                $request->position_id .
                $request->countesy_id .
                \Carbon\Carbon::parse( $request->officer_dob )->format( 'Y-m-d' )
            ),
            'code' => 'OCM-'.str_pad( $people->id.'-'.$people->officers->count() , 6 , '0' , STR_PAD_LEFT ) ,
            'organization_id' => $request->organization_id ,
            'position_id' => $request->position_id ,
            'countesy_id' => $request->countesy_id , 
            'date' => strlen( $request->officer_dob ) > 0 ? \Carbon\Carbon::parse( $request->officer_dob )->format('Y-m-d') :\Carbon\Carbon::now()->format( 'Y-m-d' ),
            'leader' => 0 ,
            'phone' => $people->officer_phone ?? $people->mobile_phone ,
            'passport' => $request->officer_passport ?? '' ,
            'email' => $request->officer_email ?? $people->email
        ]);

        $card = $people->cards()->create([
            'number' => "OCM-". str_pad( $people->id , 4 , "0" , STR_PAD_LEFT ) ,
            'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
            'people_id' => $people->id ,
            'officer_id' => $officer->id ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $user = $officer->user()->create([
            'firstname' => $people->firstname,
            'lastname' => $people->lastname,
            'email' => $people->email,
            'username' => $people->email,
            'active' => 0 ,
            'phone' => $people->mobile_phone ,
            'password' => bcrypt( 
                $officer->phone != null && strlen( $officer->phone ) > 0 
                    ? $officer->phone
                    : (
                        $people->mobile_phone != null && strlen( $people->mobile_phone ) > 0 
                            ? $request->mobile_phone 
                            : 'ocm@123456'
                    )
            ),
        ]);

        $officer->update([
            'people_id' => $people->id ,
            'user_id' => $user->id ,
        ]);

        /**
         * Assign role
         */
        $backendMemberRole = \App\Models\Role::where('name','backend')->first();
        if( $backendMemberRole != null ){
            $user->assignRole( $backendMemberRole );
        }
        $user->save();

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->position;
        $officer->people;
        return response()->json([
            'ok' => true ,
            'message' => 'បង្កើតបានជោគជ័យ !'
        ], 200);
    }
    /**
     * Create an account
     */
    public function update(Request $request){
        $officer = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        $officer->people->update([
            'firstname' => $request->people['firstname'] ,
            'lastname' => $request->people['lastname'] ,
            'enfirstname' => $request->people['enfirstname'] ,
            'enlastname' => $request->people['enlastname'] ,
            'gender' => intval($request->people['gender']) >= 0 ? $request->people['gender'] :  1 ,
            'email' => $request->people['email'] ,
            'dob' => \Carbon\Carbon::parse( $request->people['dob'] )->format('Y-m-d') ,
            'nid' => $request->people['nid'] ,
            'mobile_phone' => $request->people['mobile_phone'] ,
            'office_phone' => $request->people['office_phone'] ,
            'marry_status' => $request->people['marry_status'] != null && $request->people['marry_status'] != '' ? $request->people['marry_status'] : 'single' ,
            'address' => $request->people['address'] ?? '' ,
            'pob' => $request->people['pob'] ?? '' ,
        ]);
        $officer->update([
            'code' => $request->code ,
            'organization_id' => $request->organization_id ,
            'position_id' => $request->position_id ,
            'countesy_id' => $request->countesy_id ,
            'passport' => $request->passport ,
            'email' => $request->email ,
            'phone' => $request->phone
        ]);
        return response()->json([
            'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
            'ok' => true
        ], 200);
    }
    /**
     * Active function of the account
     */
    public function active(Request $request){
        $user = RecordModel::find($request->id) ;
        if( $user ){
            $user->active = $request->active ;
            $user->save();
            // User does exists
            return response([
                'user' => $user ,
                'ok' => true ,
                'message' => 'គណនី '.$user->name.' បានបើកដោយជោគជ័យ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !' 
                ],
                201
            );
        }
    }
    /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $people = RecordModel::find($request->id) ;
        if( $people ){
            if( $people->user != null ){
                $people->user->delete();
            }
            $people->deleted_at = \Carbon\Carbon::now() ;
            $people->save();
            // User does exists
            return response([
                'ok' => true ,
                'user' => $people ,
                'message' => 'គណនី '.$people->lastname . ' ' . $people->firstname .' បានលុបដោយជោគជ័យ !' ,
                'ok' => true 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !' ],
                201
            );
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->card;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;
        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    public function readPublic(Request $request){
        
        if( !isset( $request->key ) || strlen( $request->key ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }

        $record = RecordModel::where( 'public_key' , $request->key )->first();
        if( $record==null && ( isset( $request->key ) || strlen( $request->key ) > 0 ) ){
            $record = RecordModel::find( $request->key );
        }

        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->card;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;

        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );
            
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    public function publicPhoto(Request $request){
        
        if( !isset( $request->key ) || strlen( $request->key ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }

        $record = RecordModel::where( 'public_key' , $request->key )->first();
        if( $record==null && ( isset( $request->key ) || strlen( $request->key ) > 0 ) ){
            $record = RecordModel::find( $request->key );
        }

        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->people;

        $imageBase64 = false ;
        $imagePath = $record->image != null && \Storage::disk('public')->exists($record->image )
            ? \Storage::path( 'public/'. $record->image )
            : (
                isset( $record->user ) && $record->user->avatar_url != null && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::path( 'public/'. $record->user->avatar_url )
                : ( 
                    isset( $record->people ) && $record->people->image != null && \Storage::disk('public')->exists( $record->people->image ) 
                    ? \Storage::path( 'public/'. $record->people->image )
                    : false 
                )
            );
        if( $imagePath != false && strlen( $imagePath ) ){
            try{
                $image = new ImageResize( $imagePath );
                // $image->scale(50);
                $imageBase64 = base64_encode( $image->getImageAsString(IMAGETYPE_PNG, 10) );
            } catch (ImageResizeException $e) {
                echo "Something went wrong" . $e->getMessage();
            }
        }

        return response()->json([
            'photo' => $imageBase64 ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    // public function upload(Request $request){
    //     $user = \Auth::user();
    //     if( $user ){
    //         if( isset( $_FILES['files']['tmp_name'] ) && $_FILES['files']['tmp_name'] != "" ) {
    //             if( ( $user = RecordModel::find($request->id) ) !== null ){
    //                 $uniqeName = Storage::disk('public')->putFile( 'avatars/'.$user->id , new File( $_FILES['files']['tmp_name'] ) );
    //                 $user->avatar_url = $uniqeName ;
    //                 $user->save();
    //                 if( Storage::disk('public')->exists( $user->avatar_url ) ){
    //                     $user->avatar_url = Storage::disk('public')->url( $user->avatar_url  );
    //                     return response([
    //                         'record' => $user ,
    //                         'message' => 'ជោគជ័យក្នុងការបញ្ចូលរូបថត។'
    //                     ],200);
    //                 }else{
    //                     return response([
    //                         'record' => $user ,
    //                         'message' => 'គណនីនេះមិនមានរូបថតឡើយ។'
    //                     ],403);
    //                 }
    //             }else{
    //                 return response([
    //                     'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់គណនី។'
    //                 ],403);
    //             }
    //         }else{
    //             return response([
    //                 'result' => $_FILES ,
    //                 'message' => 'មានបញ្ហាជាមួយរូបភាពដែលអ្នកបញ្ជូនមក។'
    //             ],403);
    //         }
            
    //     }else{
    //         return response([
    //             'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
    //         ],403);
    //     }
    // }
    /**
     * Active function of the account
     */
    public function updateOrganizationCode(Request $request){
        $organization = intval( $request->organization_id ) > 0 ? \App\Models\Organization\Organization::find($request->organization_id) : null ;
        if( $organization == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អង្គភាព។'
            ],403);
        }
        $people = intval( $request->people_id ) > 0 ? \App\Models\People\People::find($request->people_id) : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់មន្ត្រីក្នុងអង្គភាព។'
            ],403);
        }
        $organizationPeople = \App\Models\Organization\OrganizationPeople::where('organization_id',$organization->id)
        ->where('people_id',$people->id)->first();
        if( $organizationPeople == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មន្ត្រីនេះមិនស្ថិតក្នុងអង្គភាពនេះឡើយ។'
            ],403);
        }
        $organizationPeople->code = $request->code ;
        $organizationPeople->save();
        // User does exists
        return response([
            'record' => $organizationPeople ,
            'ok' => true ,
            'message' => 'ជោគជ័យ !' 
        ], 200);
    }
}
