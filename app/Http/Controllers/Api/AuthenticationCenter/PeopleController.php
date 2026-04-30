<?php

namespace App\Http\Controllers\Api\AuthenticationCenter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\MobilePasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\People\People as RecordModel ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class PeopleController extends Controller
{
    private $selectFields = [
        'id',
        'public_key' ,
        'firstname' ,
        'lastname' ,
        'enfirstname' ,
        'enlastname' ,
        'gender' ,
        'dob' ,
        'mobile_phone' ,
        'office_phone' ,
        'email',
        'nid' ,
        'image' ,
        'marry_status' ,
        'father' ,
        'mother' ,
        'address' ,
        'current_address'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
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

        $peopleIds = isset( $request->ids ) ? explode(',',$request->ids) : false ;
        if( is_array( $peopleIds ) && !empty( $peopleIds ) ){
            $peopleIds = array_filter( $peopleIds , function($peopleId){
                return intval( $peopleId ) > 0 ;
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
                    is_array( $peopleIds ) && !empty( $peopleIds )
                        ?   [
                            'field' => 'id' ,
                            'value' => $peopleIds
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
                    "relationship" => 'organizations',
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
                    "relationship" => 'positions',
                    "where" => [
                        "in" => [
                            "field" => "position_id",
                            "value" => $positions
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

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields , [
            'image' => function( $people ){
                return $people['image'] != null && \Storage::disk('public')->exists( $people['image'] )
                ? \Storage::disk('public')->url( $people['image'] )
                : false ;
            }
        ]);
        $crud->setRelationshipFunctions([
        //     /** relationship name => [ array of fields name to be selected ] */
        //     "person" => ['id','firstname' , 'lastname' , 'gender' , 'dob' , 'pob' , 'picture' ] ,
        //     "roles" => ['id','name', 'tag'] ,
            "card" => [ 'id', 'uuid' , 'number' , 'people_id' ] ,
            "officers" => [ 
                'id', 'code' , 'official_date' , 'organization_id' , 'position_id' , 'rank_id' ,
                'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
                'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ]
            ]
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        /**
         * Filter the officers to get only the officer that is not admin and super admin
         */
        $builder->whereHas('users',function($query){
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
    public function store(Request $request){
        $user = \App\Models\User::where('email',$request->email)->first() ;
        if( $user ){
            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                'user' => $user ,
                'message' => 'គណនី '.$user->name.' មានក្នុងប្រព័ន្ធរួចហើយ ។' . (
                    $user->active ? " ហើយកំពុងបើកដំណើរការជាធម្មតា !" : " កំពុងត្រូវបានបិទដំណើរការ !"
                )],500
            );
        }else{
            // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
            $user = new \App\Models\User([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'username' => $request->email,
                'active' => 0 ,
                'phone' => $request->mobile_phone ,
                'password' => bcrypt( 
                    $request->mobile_phone != null && strlen( $request->mobile_phone ) > 0 ? $request->mobile_phone : '123456'
                ),
            ]);


            /**
             * Create detail information of the owner of the account
             */
            $person = \App\Models\People\People::create([
                'public_key' => md5( 
                    \Carbon\Carbon::now()->format('YmdHis') . 
                    $request->enfirstname??'' . 
                    $request->enlastname??'' . 
                    $request->gender .
                    \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) .
                    $request->nid .
                    $request->mobile_phone .
                    $request->office_phone
                ) ,
                'firstname' => $request->firstname , 
                'lastname' => $request->lastname , 
                'enfirstname' => $request->enfirstname??'' , 
                'enlastname' => $request->enlastname??'' , 
                'gender' => $request->gender , 
                'dob' => $request->dob , 
                'nid' => $request->nid , 
                'marry_status' => $request->marry_status , 
                'mobile_phone' => $request->mobile_phone , 
                'office_phone' => $request->office_phone , 
                'email' => $request->email
            ]);
            $user->people_id = $person->id ;
            $user->save();

            if( isset( $request->organizations ) && !empty( $request->organizations && $person->organizations != null ) ){
                $person->organizations()->sync( $request->organizations );
            }
            if( isset( $request->positions ) && !empty( $request->positions ) && $person->positions != null  ){
                $person->positions()->sync( $request->positions );
            }

            if( isset( $request->countesies ) && !empty( $request->countesies ) && $person->countesies != null  ){
                $person->countesies()->sync( $request->countesies );
            }

            /**
             * Assign role
             */
            $backendMemberRole = \App\Models\Role::where('name','backend')->first();
            if( $backendMemberRole != null ){
                $user->assignRole( $backendMemberRole );
            }
            
            $user->save();

            if( $user ){
                $person->user ;
                $person->organizations;
                $person->countesies;
                $person->positions;
                return response()->json([
                    'record' => $person ,
                    'ok' => true ,
                    'message' => 'គណនីបង្កើតបានជោគជ័យ !'
                ], 200);

            }else {
                return response()->json([
                    'record' => null ,
                    'ok' => false ,
                    'message' => 'បរាជ័យក្នុងការបង្កើតគណនី !'
                ], 500);
            }
        }
    }
    /**
     * Create an account
     */
    // public function update(Request $request){
    //     $person = isset( $request->id ) && $request->id > 0 ? RecordModel::find($request->id) : (
    //         isset( $request->email ) && $request->email != "" ? RecordModel::where('email',$request->email)->first() : null
    //     );
    //     $user = \Auth::user() == null ? null : \Auth::user() ;
    //     if( $person != null ){
    //         /**
    //          * Update fields of table by one by one and check it value
    //          */
    //         /**
    //          * String type
    //          */
    //         $stringFields = [
    //             // Office People Profile
    //             'firstname','lastname','enfirstname','enlastname','email','nid','mobile_phone','office_phone','address','current_address','pob','body_condition','body_condition_desp','nationality','national' ,
    //             // Father
    //             'father_firstname','father_lastname','father_enfirstname','father_enlastname','father_nationality','father_national','father_nid','father_pob','father_address','father_death','father_profession',
    //             // Mother
    //             'mother_firstname','mother_lastname','mother_enfirstname','mother_enlastname','mother_nationality','mother_national','mother_nid','mother_pob','mother_address','mother_death','mother_profession',
    //             // Emergency
    //             'emergency_lastname','emergency_firstname','emergency_gender','emergency_relationship','emergency_profession','emergency_phone','emergency_email','emergency_address'
    //             ];
    //         foreach( $stringFields as $field ){
    //             if( isset( $request->$field ) && $field == 'body_condition_desp' ){
    //                 $person->$field = $request->body_condition == 0 ? '' : $request->$field;
    //             }
    //             else if( isset( $request->$field ) && strlen( $request->$field ) > 0 ){
    //                 $person->$field = $request->$field;
    //             }
    //         }
    //         $person->save();
    //         /**
    //          * Date type
    //          */
    //         $dateFields = ['dob','father_dob','mother_dob'];
    //         foreach( $dateFields as $field ){
    //             if( isset( $request->people[ $field ] ) && strlen( $request->people[ $field ] ) > 0 ){
    //                 $person->$field = \Carbon\Carbon::parse( $request->$field )->format('Y-m-d');
    //             }
    //         }
    //         $person->save();
    //         /**
    //          * Number type
    //          */

    //         $person->update([
    //             // User Profile Background
    //             'gender' => intval($request->gender) >= 0 ? intval( $request->gender ) :  1 ,                
    //             'marry_status' => $request->marry_status != null && $request->marry_status != '' ? $request->marry_status : 'single' ,
                
    //             'address_province_id' => isset( $request->address_province_id ) && intval( $request->address_province_id ) > 0 ? intval( $request->address_province_id ) : 0 ,
    //             'address_district_id' => isset( $request->address_district_id ) && intval( $request->address_district_id ) > 0 ? intval( $request->address_district_id ) : 0 ,
    //             'address_commune_id' => isset( $request->address_commune_id ) && intval( $request->address_commune_id ) > 0 ? intval( $request->address_commune_id ) : 0 ,
    //             'address_village_id' => isset( $request->address_village_id ) && intval( $request->address_village_id ) > 0 ? intval( $request->address_village_id ) : 0 ,
                
    //             'current_address_province_id' => isset( $request->current_address_province_id ) && intval( $request->current_address_province_id ) > 0 ? intval( $request->current_address_province_id ) : 0 ,
    //             'current_address_district_id' => isset( $request->current_address_district_id ) && intval( $request->current_address_district_id ) > 0 ? intval( $request->current_address_district_id ) : 0 ,
    //             'current_address_commune_id' => isset( $request->current_address_commune_id ) && intval( $request->current_address_commune_id ) > 0 ? intval( $request->current_address_commune_id ) : 0 ,
    //             'current_address_village_id' => isset( $request->current_address_village_id ) && intval( $request->current_address_village_id ) > 0 ? intval( $request->current_address_village_id ) : 0 ,
                
    //             'pob_province_id' => isset( $request->pob_province_id ) && intval( $request->pob_province_id ) > 0 ? intval( $request->pob_province_id ) : 0 ,
    //             'pob_district_id' => isset( $request->pob_district_id ) && intval( $request->pob_district_id ) > 0 ? intval( $request->pob_district_id ) : 0 ,
    //             'pob_commune_id' => isset( $request->pob_commune_id ) && intval( $request->pob_commune_id ) > 0 ? intval( $request->pob_commune_id ) : 0 ,
    //             'pob_village_id' => isset( $request->pob_village_id ) && intval( $request->pob_village_id ) > 0 ? intval( $request->pob_village_id ) : 0 ,

    //             // father                            
    //             'father_address_province_id' => isset( $request->father_address_province_id ) && intval( $request->father_address_province_id ) > 0 ? intval( $request->father_address_province_id ) : 0 ,
    //             'father_address_district_id' => isset( $request->father_address_district_id ) && intval( $request->father_address_district_id ) > 0 ? intval( $request->father_address_district_id ) : 0 ,
    //             'father_address_commune_id' => isset( $request->father_address_commune_id ) && intval( $request->father_address_commune_id ) > 0 ? intval( $request->father_address_commune_id ) : 0 ,
    //             'father_address_village_id' => isset( $request->father_address_village_id ) && intval( $request->father_address_village_id ) > 0 ? intval( $request->father_address_village_id ) : 0 ,

    //             // mother
    //             'mother_address_province_id' => isset( $request->mother_address_province_id ) && intval( $request->mother_address_province_id ) > 0 ? intval( $request->mother_address_province_id ) : 0 ,
    //             'mother_address_district_id' => isset( $request->mother_address_district_id ) && intval( $request->mother_address_district_id ) > 0 ? intval( $request->mother_address_district_id ) : 0 ,
    //             'mother_address_commune_id' => isset( $request->mother_address_commune_id ) && intval( $request->mother_address_commune_id ) > 0 ? intval( $request->mother_address_commune_id ) : 0 ,
    //             'mother_address_village_id' => isset( $request->mother_address_village_id ) && intval( $request->mother_address_village_id ) > 0 ? intval( $request->mother_address_village_id ) : 0 ,
                
    //             // Emergency 
    //             'emergency_address_province_id' => isset( $request->emergency_address_province_id ) && intval( $request->emergency_address_province_id ) > 0 ? $request->emergency_address_province_id : 0 ,
    //             'emergency_address_district_id' => isset( $request->emergency_address_district_id ) && intval( $request->emergency_address_district_id ) > 0 ? $request->emergency_address_district_id : 0 ,
    //             'emergency_address_commune_id' => isset( $request->emergency_address_commune_id ) && intval( $request->emergency_address_commune_id ) > 0 ? $request->emergency_address_commune_id : 0 ,
    //             'emergency_address_village_id' => isset( $request->emergency_address_village_id ) && intval( $request->emergency_address_village_id ) > 0 ? $request->emergency_address_village_id : 0 ,
                
    //             'updated_by' => $user == null ? 0 : $user->id ,
    //             'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
    //         ]);

    //         return response()->json([
    //             'record' => $person ,
    //             'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
    //             'ok' => true
    //         ], 200);
    //     }else{
    //         // អ្នកប្រើប្រាស់មិនមាន
    //         return response([
    //             'record' => null ,
    //             'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
    //             'ok' => false
    //         ], 403);
    //     }
    // }

    public function update(Request $request){
        $person = isset($request->id) && intval( $request->id ) > 0 
            ? RecordModel::find($request->id) 
            :(
                isset($request->email) && strlen( $request->email ) > 0
                    ? RecordModel::where('email', $request->email)->first() 
                    : null
            );
        
        $user = \Auth::user() == null ? null : \Auth::user();
        $fillableFields = [ 
            'firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 'marry_status' , 'pob' , 'current_address' , 'address' , 'education_level' ,
            'mobile_phone' , 'office_phone' , 'national' , 'nationality' , 'email', 'passport' , 'body_condition' , 'body_condition_desp' , 
            'nid' , 'nid_start_at' , 'nid_expired_at' , 'dob' , 'gender' , 'countesy_id' , 
            'father_firstname' , 'father_lastname' , 'father_enfirstname' , 'father_enlastname' , 'father_pob' , 'father_address' , 'father_profession' , 'father_death' , 'father_dob' , 'father_national' , 'father_nationality' , 'father_nid' ,
            'mother_firstname' , 'mother_lastname' , 'mother_enfirstname' , 'mother_enlastname' , 'mother_pob' , 'mother_address' , 'mother_profession' , 'mother_death' , 'mother_dob' , 'father_national' , 'father_nationality' , 'father_nid' ,
            'emergency_firstname' , 'emergency_lastname' , 'emergency_gender' , 'emergency_phone' , 'emergency_email' , 'emergency_address' , 'emergency_profession' , 'emergency_relationship' ,
            'province_id' , 'disctrct_id' , 'commune_id' , 'village_id' , 
            'current_address_province_id' , 'current_address_district_id' , 'current_address_commune_id' , 'current_address_village_id' ,
            'pob_province_id' , 'pob_district_id' , 'pob_commune_id' , 'pob_village_id' ,
            'father_address_province_id' , 'father_address_district_id' , 'father_address_commune_id' , 'father_address_village_id' ,
            'mother_address_province_id' , 'mother_address_district_id' , 'mother_address_commune_id' , 'mother_address_village_id' ,
            'emergency_address_province_id' , 'emergency_address_district_id' , 'emergency_address_commune_id' , 'emergency_address_village_id'
        ];

        // Information of People
        $firstname = isset( $request->people['firstname'] ) && strlen( $request->people['firstname'] ) >= 0 ? $request->people['firstname'] : false ;
        $lastname = isset( $request->people['lastname'] ) && strlen( $request->people['lastname'] ) >= 0 ? $request->people['lastname'] : false ;
        $enfirstname = isset( $request->people['enfirstname'] ) && strlen( $request->people['enfirstname'] ) >= 0 ? $request->people['enfirstname'] : false ;
        $enlastname = isset( $request->people['enlastname'] ) && strlen( $request->people['enlastname'] ) >= 0 ? $request->people['enlastname'] : false ;
        $marry_status = isset( $request->people['marry_status'] ) && strlen( $request->people['marry_status'] ) > 0 && in_array( trim( $request->people['marry_status'] ) , [ 'married' , 'single' , 'divorced' ] ) ? $request->people['marry_status'] : false ;
        $pob = isset( $request->people['pob'] ) && strlen( $request->people['pob'] ) >= 0 ? $request->people['pob'] : false ;
        $current_address = isset( $request->people['current_address'] ) && strlen( $request->people['current_address'] ) >= 0 ? $request->people['current_address'] : false ;
        $address = isset( $request->people['address'] ) && strlen( $request->people['address'] ) >= 0 ? $request->people['address'] : false ;
        $mobile_phone = isset( $request->people['mobile_phone'] ) && strlen( $request->people['mobile_phone'] ) >= 0 ? $request->people['mobile_phone'] : false ;
        $office_phone = isset( $request->people['office_phone'] ) && strlen( $request->people['office_phone'] ) >= 0 ? $request->people['office_phone'] : false ;
        $national = isset( $request->people['national'] ) && strlen( $request->people['national'] ) >= 0 ? $request->people['national'] : false ;
        $nationality = isset( $request->people['nationality'] ) && strlen( $request->people['nationality'] ) >= 0 ? $request->people['nationality'] : false ;
        $email = isset( $request->people['email'] ) && strlen( $request->people['email'] ) >= 0 ? $request->people['email'] : false ;
        // $passport = isset( $request->people['passport'] ) && strlen( $request->people['passport'] ) ? $request->people['passport'] : false ;
        $body_condition_desp = isset( $request->people['body_condition_desp'] ) && strlen( $request->people['body_condition_desp'] ) ? $request->people['body_condition_desp'] : false ;
        $nid = isset( $request->people['nid'] ) && strlen( $request->people['nid'] ) ? $request->people['nid'] : false ;
        
        $education_level = isset( $request->people['education_level'] ) && strlen( $request->people['education_level'] ) > 0 ? $request->people['education_level'] : false ;

        $dob = isset( $request->people['dob'] ) && strlen( $request->people['dob'] ) ? \Carbon\Carbon::parse( $request->people['dob'] ) : false ;
        $nid_start_at = isset( $request->people['nid_start_at'] ) && strlen( $request->people['nid_start_at'] ) ? \Carbon\Carbon::parse( $request->people['nid_start_at'] ) : false ;
        $nid_expired_at = isset( $request->people['nid_expired_at'] ) && strlen( $request->people['nid_expired_at'] ) ? \Carbon\Carbon::parse( $request->people['nid_expired_at'] ) : false ;
    
        $gender = isset( $request->people['gender'] ) && intval( $request->people['gender'] ) > 0 ? intval( $request->people['gender'] )  : false ;
        $countesy_id = isset( $request->people['countesy_id'] ) && intval( $request->people['countesy_id'] ) > 0 ? intval( $request->people['countesy_id'] )  : false ;
        $body_condition = isset( $request->people['body_condition'] ) && intval( $request->people['body_condition'] ) > 0 ? intval( $request->people['body_condition'] ) : false ;
        
        // Father fields
        $father_firstname = isset( $request->people['father_firstname'] ) && strlen( $request->people['father_firstname'] ) >= 0 ? $request->people['father_firstname'] : false ;
        $father_lastname = isset( $request->people['father_lastname'] ) && strlen( $request->people['father_lastname'] ) >= 0 ? $request->people['father_lastname'] : false ;
        $father_enfirstname = isset( $request->people['father_enfirstname'] ) && strlen( $request->people['father_enfirstname'] ) >= 0 ? $request->people['father_enfirstname'] : false ;
        $father_enlastname = isset( $request->people['father_enlastname'] ) && strlen( $request->people['father_enlastname'] ) >= 0 ? $request->people['father_enlastname'] : false ;
        $father_pob = isset( $request->people['father_pob'] ) && strlen( $request->people['father_pob'] ) >= 0 ? $request->people['father_pob'] : false ;
        $father_address = isset( $request->people['father_address'] ) && strlen( $request->people['father_address'] ) >= 0 ? $request->people['father_address'] : false ;
        $father_profession = isset( $request->people['father_profession'] ) && strlen( $request->people['father_profession'] ) >= 0 ? $request->people['father_profession'] : false ;
        $father_death = isset( $request->people['father_death'] ) && strlen( $request->people['father_death'] ) >= 0 ? $request->people['father_death'] : false ;
        $father_dob = isset( $request->people['father_dob'] ) && strlen( $request->people['father_dob'] ) >= 0 ? $request->people['father_dob'] : false ;
        // Mother fields
        $mother_firstname = isset( $request->people['mother_firstname'] ) && strlen( $request->people['mother_firstname'] ) >= 0 ? $request->people['mother_firstname'] : false ;
        $mother_lastname = isset( $request->people['mother_lastname'] ) && strlen( $request->people['mother_lastname'] ) >= 0 ? $request->people['mother_lastname'] : false ;
        $mother_enfirstname = isset( $request->people['mother_enfirstname'] ) && strlen( $request->people['mother_enfirstname'] ) >= 0 ? $request->people['mother_enfirstname'] : false ;
        $mother_enlastname = isset( $request->people['mother_enlastname'] ) && strlen( $request->people['mother_enlastname'] ) >= 0 ? $request->people['mother_enlastname'] : false ;
        $mother_pob = isset( $request->people['mother_pob'] ) && strlen( $request->people['mother_pob'] ) >= 0 ? $request->people['mother_pob'] : false ;
        $mother_address = isset( $request->people['mother_address'] ) && strlen( $request->people['mother_address'] ) >= 0 ? $request->people['mother_address'] : false ;
        $mother_profession = isset( $request->people['mother_profession'] ) && strlen( $request->people['mother_profession'] ) >= 0 ? $request->people['mother_profession'] : false ;
        $mother_death = isset( $request->people['mother_death'] ) && strlen( $request->people['mother_death'] ) >= 0 ? $request->people['mother_death'] : false ;
        $mother_dob = isset( $request->people['mother_dob'] ) && strlen( $request->people['mother_dob'] ) >= 0 ? $request->people['mother_dob'] : false ;

        // Emgerfency fields
        $emergency_firstname = isset( $request->people['father_firstname'] ) && strlen( $request->people['father_firstname'] ) >= 0 ? $request->people['father_firstname'] : false ;
        $emergency_lastname = isset( $request->people['father_lastname'] ) && strlen( $request->people['father_lastname'] ) >= 0 ? $request->people['father_lastname'] : false ;
        $emergency_gender = isset( $request->people['emergency_gender'] ) && intval( $request->people['emergency_gender'] ) >= 0 ? $request->people['emergency_gender'] : false ;
        $emergency_phone = isset( $request->people['emergency_phone'] ) && strlen( $request->people['emergency_phone'] ) >= 0 ? $request->people['emergency_phone'] : false ;
        $emergency_email = isset( $request->people['emergency_email'] ) && strlen( $request->people['emergency_email'] ) >= 0 ? $request->people['emergency_email'] : false ;
        $emergency_address = isset( $request->people['emergency_address'] ) && strlen( $request->people['emergency_address'] ) >= 0 ? $request->people['emergency_address'] : false ;
        $emergency_profession = isset( $request->people['emergency_profession'] ) && strlen( $request->people['emergency_profession'] ) >= 0 ? $request->people['emergency_profession'] : false ;
        
        $father_national = isset( $request->people['father_national'] ) && strlen( $request->people['father_national'] ) >= 0 ? $request->people['father_national'] : false ;
        $father_nationality = isset( $request->people['father_nationality'] ) && strlen( $request->people['father_nationality'] ) >= 0 ? $request->people['father_nationality'] : false ;
        $mother_national = isset( $request->people['mother_national'] ) && strlen( $request->people['mother_national'] ) >= 0 ? $request->people['mother_national'] : false ;
        $mother_nationality = isset( $request->people['mother_nationality'] ) && strlen( $request->people['mother_nationality'] ) >= 0 ? $request->people['mother_nationality'] : false ;
        $father_nid = isset( $request->people['father_nid'] ) && strlen( $request->people['father_nid'] ) ? $request->people['father_nid'] : false ;
        $mother_nid = isset( $request->people['mother_nid'] ) && strlen( $request->people['mother_nid'] ) ? $request->people['mother_nid'] : false ;

        $address_province_id = isset( $request->people['address_province_id'] ) && intval( $request->people['address_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['address_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $address_district_id = isset( $request->people['address_district_id'] ) && intval( $request->people['address_address_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['address_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $address_commune_id = isset( $request->people['address_commune_id'] ) && intval( $request->people['address_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['address_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $address_village_id = isset( $request->people['address_village_id'] ) && intval( $request->people['address_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['address_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        $current_address_province_id = isset( $request->people['current_address_province_id'] ) && intval( $request->people['current_address_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['current_address_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $current_address_district_id = isset( $request->people['current_address_district_id'] ) && intval( $request->people['current_address_address_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['current_address_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $current_address_commune_id = isset( $request->people['current_address_commune_id'] ) && intval( $request->people['current_address_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['current_address_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $current_address_village_id = isset( $request->people['current_address_village_id'] ) && intval( $request->people['current_address_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['current_address_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        $pob_province_id = isset( $request->people['pob_province_id'] ) && intval( $request->people['pob_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['pob_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $pob_district_id = isset( $request->people['pob_district_id'] ) && intval( $request->people['pob_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['pob_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $pob_commune_id = isset( $request->people['pob_commune_id'] ) && intval( $request->people['pob_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['pob_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $pob_village_id = isset( $request->people['pob_village_id'] ) && intval( $request->people['pob_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['pob_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        $father_address_province_id = isset( $request->people['father_address_province_id'] ) && intval( $request->people['father_address_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['father_address_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $father_address_district_id = isset( $request->people['father_address_district_id'] ) && intval( $request->people['father_address_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['father_address_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $father_address_commune_id = isset( $request->people['father_address_commune_id'] ) && intval( $request->people['father_address_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['father_address_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $father_address_village_id = isset( $request->people['father_address_village_id'] ) && intval( $request->people['father_address_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['father_address_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        $mother_address_province_id = isset( $request->people['mother_address_province_id'] ) && intval( $request->people['mother_address_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['mother_address_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $mother_address_district_id = isset( $request->people['mother_address_district_id'] ) && intval( $request->people['mother_address_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['mother_address_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $mother_address_commune_id = isset( $request->people['mother_address_commune_id'] ) && intval( $request->people['mother_address_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['mother_address_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $mother_address_village_id = isset( $request->people['mother_address_village_id'] ) && intval( $request->people['mother_address_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['mother_address_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        $emergency_address_province_id = isset( $request->people['emergency_address_province_id'] ) && intval( $request->people['emergency_address_province_id'] ) > 0 
        ? (
            ( $p = \App\Models\Location\Province::find( $request->people['emergency_address_province_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $emergency_address_district_id = isset( $request->people['emergency_address_district_id'] ) && intval( $request->people['emergency_address_district_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\District::find( $request->people['emergency_address_district_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $emergency_address_commune_id = isset( $request->people['emergency_address_commune_id'] ) && intval( $request->people['emergency_address_commune_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Commune::find( $request->people['emergency_address_commune_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;
        $emergency_address_village_id = isset( $request->people['emergency_address_village_id'] ) && intval( $request->people['emergency_address_village_id'] ) > 0 
            ? (
            ( $p = \App\Models\Location\Village::find( $request->people['emergency_address_village_id'] ) ) != null 
                ? $p->id 
                : false
        )
        : false ;

        foreach( $fillableFields AS $index => $field ){
            if( isset( $$field ) && $$field !== false ){
                $peopleData[ $field ] = $$field ;
            }
        }

        $peopleData['updated_by'] = $user == null ? 0 : $user->id ;
        $peopleData['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

        if($person != null && $request->people != null){
            $person->update( $peopleData );
            $record = $person->officers()->first();

            $record->user;
            $record->card;
            $record->rank;
            $record->countesy;
            $record->position;
            $record->organization;
            $record->people;

            if( $record->people != null ){
                $record->people->wedding_certificates = $record->people->weddingCertificates->map(function( $weddingCertificate ){
                    $weddingCertificate->birthCertificates;
                    return $weddingCertificate;
                }) ;
                $record->people->passports;

                $certificates['first'] = $record->people->certificatesHighSchool();
                $certificates['middle'] = $record->people->certificatesPostGraduated();
                $certificates['others'] = $record->people->certificatesOthers();
                // $record->people->birthCertificates;
                $record->people->selfBirthCertificates = $record->people->selfBirthCertificates()->get();
                $record->people->certificates = $certificates ;
                $record->people->languages;
                $record->jobBackgrounds;
                $record->officer_ranks = $record->officerRanks->map(function($officerRank){
                    $officerRank->rank;
                    $officerRank->countesy;
                    return $officerRank;
                });
                $record->krob_khans = $record->officer_ranks;
                $record->ranking_by_certificates = $record->rankingByCertificates->map(function($rank){
                    $rank->rank;
                    $rank->previousRank;
                    return $rank;
                });
                $record->ranking_by_workings = $record->rankingByWorkings->map(function($rank){
                    $rank->rank;
                    $rank->previousRank;
                    return $rank;
                });
                $record->rankingByWorkings;
                $record->pendingWorks;
                $record->paneltyHistories;
                $record->medalHistories;

            }

            $record->job = $record->getCurrentJob();
            if( $record->job != null && $record->job->organizationStructurePosition != null ){
                $record->job->organizationStructurePosition->position;
                $record->job->organizationStructurePosition->permissions;
                if( $record->job->organizationStructurePosition->organizationStructure != null ){
                    $tpid = $record->job->organizationStructurePosition->organizationStructure->tpid;
                    // Convert string into array
                    $indexes = array_filter(explode(':', $tpid), fn($v) => $v !== '');
                    $indexes = array_values($indexes); // reindex

                    if (!isset($indexes[3])) {  //use count function is > 3
                        // index 3 doesn't exist → use index 2
                        $targetIndexes = isset($indexes[2]) ? [$indexes[2]] : [];
                    } else {
                        // index 3 exists → use index 3 and everything after
                        $targetIndexes = array_slice($indexes, 3);
                    }
                    $organizations = [];
                    foreach ($targetIndexes as $id) {
                        // Example: fetch organization by ID
                        $organization = \App\Models\Organization\OrganizationStructure::where('id', $id)->first();
                        $organizations[$id] = $organization->organization;
                    }
                    $record->job->organizationStructurePosition->organizationStructure->organizations = $organizations;
                    // $record->job->organizationStructurePosition->organizationStructure->organization;
                }
            }

            $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
                ? \Storage::disk('public')->url( $record->image )
                : (
                    $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                    ? \Storage::disk('public')->url( $record->user->avatar_url )
                    : false
                );

            return response()->json([
                'record' => $record,
                'message' => 'កែប្រែព័ត៌មានរួចរាល់ !',
                'ok' => true
            ], 200);
        } else {
            return response([
                'record' => null,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។',
                'ok' => false
            ], 403);
        }
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
        $record->countesies;
        $record->positions;
        $record->organizations;
        $record->organizationPeople->map(function($organizationPivot){
            $organizationPivot->organization;
            return $organizationPivot;
        });
        $record = $record->toArray();

        $record['image'] = $record['image'] != null && trim($record['image'] ) != "" && \Storage::disk('public')->exists( $record['image'] )
            ? \Storage::disk('public')->url( $record['image'] )
            : (
                $record['user'] != null && $record['user']['avatar_url'] != null && trim($record['user']['avatar_url']) != "" && \Storage::disk('public')->exists( $record['user']['avatar_url'] )
                ? \Storage::disk('public')->url( $record['user']['avatar_url'] )
                : false
            );

        return response()->json([
            'record' => [
                'card' => $record['card'] ,
                'countesies' => $record['countesies'] ,
                'firstname' => $record['firstname'] ,
                'lastname' => $record['lastname'] ,
                'enfirstname' => $record['enfirstname'] ,
                'enlastname' => $record['enlastname'] ,
                'gender' => $record['gender'] ,
                'id' => $record['id'] ,
                'image' => $record['image'] ,
                'organization_people' => $record['organization_people'] ,
                'organizations' => $record['organizations'] ,
                'positions' => $record['positions']
            ] ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
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