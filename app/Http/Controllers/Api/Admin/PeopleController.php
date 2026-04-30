<?php

namespace App\Http\Controllers\Api\Admin;

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
    public function update(Request $request){
        $person = isset( $request->id ) && $request->id > 0 ? RecordModel::find($request->id) : (
            isset( $request->email ) && $request->email != "" ? RecordModel::where('email',$request->email)->first() : null
        );
        if( $person && $person->update([
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'gender' => intval($request->gender) >= 0 ? $request->gender :  1 ,
            'email' => $request->email ,
            'dob' => $request->dob ,
            'nid' => $request->nid ,
            'mobile_phone' => $request->mobile_phone ,
            'office_phone' => $request->office_phone ,
            'marry_status' => $request->marry_status != null && $request->marry_status != '' ? $request->marry_status : 'single'
        ]) == true ){;
            if( isset( $request->organizations ) && is_array( $request->organizations ) && $person->organizations != null ){
                $person->organizations()->sync( $request->organizations );
            }
            if( isset( $request->positions ) && is_array( $request->positions ) && $person->positions != null ){
                $person->positions()->sync( $request->positions );
            }
            if( isset( $request->countesies ) && is_array( $request->countesies ) && $person->countesies != null ){
                $person->countesies()->sync( $request->countesies );
            }
            return response()->json([
                'record' => $person ,
                'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                'ok' => true
            ], 200);
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'record' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
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
    public function addKid(){
        if( 
            (
                strlen( $request->firstname ) > 0 &&
                strlen( $request->lastname ) > 0 &&
                strlen( $request->gender ) > 0 &&
                strlen( $request->dob ) > 0
            ) && 
            (
                intval( $request->father_id ) > 0 ||
                intval( $request->mother_id ) > 0
            )
        )

        $father = intval( $request->father_id ) > 0 ? RecordModel::find( $request->father_id ) : null ;
        $mother = intval( $request->mother_id ) > 0 ? RecordModel::find( $request->mother_id ) : null ;
        
        if( $father == null && $mother == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់ពី ឪពុក ម្ដាយ។'
            ],500);
        }

        $record = [
            'firstname' => $request->firstname , // required
            'lastname' => $request->lastname , // required
            'gender' => $request->gender , // required
            'dob' => $request->dob , // required
            'public_key' => md5( 
                \Carbon\Carbon::now()->format('YmdHis') . 
                $request->enfirstname??'' . 
                $request->enlastname??'' . 
                $request->gender??'' .
                \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) .
                $request->nid??'' .
                $request->mobile_phone??'' .
                $request->office_phone??''
            ) ,
            'enfirstname' => $request->enfirstname??'' , 
            'enlastname' => $request->enlastname??'' , 
            'nid' => $request->nid??'' , 
            'marry_status' => $request->marry_status??'' , 
            'mobile_phone' => $request->mobile_phone??'' , 
            'office_phone' => $request->office_phone??'' , 
            'email' => $request->email??'' ,
            // Family
            'father_id' => $father != null ? $father->id : 0 ,
            'mother_id' => $mother != null ? $mother->id : 0
        ];
        $checkConditions = 
            [
                'firstname' => $request->firstname ,
                'lastname' => $request->lastname ,
                'dob' => $request->dob ,
                'gender' => $request->gender
            ]; 
        $kid = RecordModel::where([
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d') ,
            'gender' => $request->gender
        ])->where(function($query)use( $request ){
            $query->where(function($query)use($request){
                $query->where('firstname','ILIKE', str_replace(' ', '' , $request->firstname ) )
                ->where('lastname','ILIKE', str_replace(' ', '' , $request->lastname ) );
                if( strlen( $request->enlastname ) > 0 ){
                    $query->where( 'enlastname' , strtolower( str_replace(' ', '' , $request->enfirstname ) ) );
                }
                if( strlen( $request->enfirstname ) > 0 ){
                    $query->where( 'enfirstname' , strtolower( str_replace(' ', '' , $request->enfirstname ) ) );
                }
            });
        })->first();
        
        if( $kid != null ){
            if( $father != null ){
                if( $kid->father_id == $father->id ){
                    return response()->json([
                        'ok' => true ,
                        'message' => 'កូននេះស្ថិតក្រោម ឪពុកនេះរួចហើយ។'
                    ],200);
                }else{
                    $kid->update([
                        'father_id' => $father->id
                    ]);
                    return response()->json([
                        'ok' => true ,
                        'message' => 'បញ្ចូលរួចរាល់។'
                    ],200);
                }
            }
            if( mother != null ){
                if( $kid->mother_id == $mother->id ){
                    return response()->json([
                        'ok' => true ,
                        'message' => 'កូននេះស្ថិតក្រោម ម្ដាយនេះរួចហើយ។'
                    ],200);
                }else{
                    $kid->update([
                        'mother_id' => $mother->id
                    ]);
                    return response()->json([
                        'ok' => true ,
                        'message' => 'បញ្ចូលរួចរាល់។'
                    ],200);
                }
            }
        }else{
            $kid = RecordModel::create( $record );
            return response()->json([
                'ok' => true ,
                'message' => 'បញ្ចូលរួចរាល់។'
            ],200);
        }
    }
    public function removeKid(){

    }
}
