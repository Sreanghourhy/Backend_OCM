<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\MobilePasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\User as RecordModel ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    private $selectFields = [
        'id',
        'firstname' ,
        'lastname' ,
        'email',
        'username' ,
        'phone' ,
        'active' ,
        'avatar_url' ,
        'avatar' ,
        'people_id' ,
        'last_login' ,
        'login_count' ,
        'ip' ,
        'created_at'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $positions = isset( $request->positions ) && strlen( $request->positions ) > 0
            ? array_filter( explode( ',' , $request->positions ) , function($item ){ return intval( $item ) > 0 ; } )
            : false ;
        $organizations = isset( $request->organizations ) && strlen( $request->organizations ) > 0
            ? array_filter( explode( ',' , $request->organizations ) , function($item ){ return intval( $item ) > 0 ; } )
            : false ;

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
            "pivots" => [
                // $unit ?
                // [
                //     "relationship" => 'units',
                //     "where" => [
                //         "in" => [
                //             "field" => "id",
                //             "value" => [$request->unit]
                //         ],
                //         "not"=> [
                //             [
                //                 "field" => 'fieldName' ,
                //                 "value"=> 'value'
                //             ]
                //         ],
                //         "like"=>  [
                //             [
                //             "field"=> 'fieldName' ,
                //             "value"=> 'value'
                //             ]
                //         ]
                //     ]
                // ]
                // : []
            ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'firstname','lastname', 'email', 'username' , 'phone' ,
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
            /** relationship name => [ array of fields name to be selected ] */
            "officer" => [ 'id' , 'code' , 'official_date' , 'countesy_id' , 'organization_id' , 'position_id' , 'people_id' ,
                'people' => [ 'id','firstname' , 'lastname' ,'enfirstname' , 'enlastname' , 'gender' , 'dob' , 'pob' , 'picture' , 'mobile_phone' , 'office_phone' , 'nid' , 'email' , 'marry_status' ] ,
                'card' => [ 'id' , 'uid' , 'number' , 'active' , 'start' , 'end' ] ,
                'countesy' => [ 'id' , 'name' , 'desp' , 'active' , 'prefix' , 'record_index' ] ,
                'organization' => [ 'id' , 'name' , 'desp' , 'active' , 'prefix' , 'record_index' ] ,
                'position' => [ 'id' , 'name' , 'desp' , 'active' , 'prefix' , 'record_index' ]
            ] ,
            "roles" => ['id','name', 'tag']
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        /**
         * Roles filter
         */
        if( true ){
            $builder->whereHas('roles',function( $query ) {
                $query->whereNotIn('role_id',[1,2]);
            });
        }
        /**
         * Positions filter
         */
        // if( $positions ){
        //     $builder->whereHas('person',function( $query ) use($positions) {
        //         $query->whereHas('positions',function($query) use( $positions ){
        //             $query->whereIn('position_id',$positions);
        //         });
        //     });
        // }

        /**
         * Organization filter
         */
        // if( $organizations ){
        //     $builder->whereHas('person',function( $query ) use($organizations) {
        //         $query->whereHas('organizations',function($query) use( $organizations ){
        //             $query->whereIn('organization_id',$organizations);
        //         });
        //     });
        // }

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($user){
            $people = isset( $user['person'] ) && $user['person']['id'] > 0 ? \App\Models\People\People::find( $user['person']['id'] ) : null ;
            return $user;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['positions'] = $positions;
        $responseData['organizations'] = $organizations;
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
                )],201
            );
        }else{
            // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
            $user = new \App\Models\User([
                'email' => $request->email,
                'active' => $request->active == true || $request->active == 1 ? 1 : 0 ,
                'password' => bcrypt($request->password) ,
                'phone' => $request->phone ,
                'username' => $request->username != null && strlen( $request->username ) > 0 ? $request->username : $request->email 
            ]);

            /**
             * Create detail information of the owner of the account
             */
            $person = \App\Models\People\People::create([
                'firstname' => $user->firstname , 
                'lastname' => $user->lastname , 
                'gender' => $user->gender != null && strlen( $user->gender ) > 0 ? $user->gender : 1  , 
                'dob' => $user->dob != null && strlen( $user->dob ) > 0 ? $user->dob : \Carbon\Carbon::now()->format('Y-m-d')  , 
                'mobile_phone' => $user->phone , 
                'email' => $user->email , 
                'image' => $user->avatar_url , 
            ]);
            $user->people_id = $person->id ;
            $user->save();

            /**
             * Assign role
             */
            $backendMemberRole = \App\Models\Role::backend()->where('key_name','officer')->first();
            if( $request->role > 0 ){
                $backendMemberRole = \App\Models\Role::find( $request->role );
            }
            if( $backendMemberRole != null ){
                $user->assignRole( $backendMemberRole );
            }
            
            $user->save();

            if( $user ){
                
                return response()->json([
                    'record' => $user ,
                    'ok' => true ,
                    'message' => 'គណនីបង្កើតបានជោគជ័យ !'
                ], 200);

            }else {
                return response()->json([
                    'user' => null ,
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
        $user = isset( $request->id ) && $request->id > 0 ? RecordModel::find($request->id) : (
            isset( $request->email ) && $request->email != "" ? RecordModel::where('email',$request->email)->first() : null
        );
        if( $user && $user->update([
            'email' => $request->email ,
            'username' => $request->username ,
            'phone' => $request->phone
        ]) == true ){
            $user->roles()->sync([ $request->role]);
            return response()->json([
                'record' => [
                    'user_id' => $user->id ,
                    'roles' => $user->roles
                ] ,
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
        $user = \App\Models\User::find($request->id) ;
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
     * Unactive function of the account
     */
    public function unactive(Request $request){
        $user = \App\Models\User::find($request->id) ;
        if( $user ){
            $user->active = 0 ;
            $user->save();
            // User does exists
            return response([
                'ok' => true ,
                'user' => $user ,
                'message' => 'គណនី '.$user->name.' បានបិទដោយជោគជ័យ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !' ],
                201
            );
        }
    }
    /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $user = \App\Models\User::find($request->id) ;
        if( $user ){
            if( $user->person != null ){
                $user->person->delete();
            }
            $user->active = 0 ;
            $user->deleted_at = \Carbon\Carbon::now() ;
            $user->save();
            // User does exists
            return response([
                'ok' => true ,
                'user' => $user ,
                'message' => 'គណនី '.$user->name.' បានលុបដោយជោគជ័យ !' ,
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
    /**
     * Function Restore an account from SoftDeletes
     */
    public function restore(Request $request){
        if( $user = \App\Models\User::restore($request->id) ){
            return response([
                'user' => $user ,
                'ok' => true ,
                'message' => 'គណនី '.$user->name.' បានយកត្រឡប់មិវិញដោយជោគជ័យ !'
                ],200
            );
        }
        return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'មិនមានគណនីនេះឡើយ !'
            ],201
        );
    }

    public function forgotPassword(Request $request){
        if( $request->email != "" ){
            $user = \App\Models\User::where('email',$request->email )->first();
            if ($user) {
                $user -> forgot_password_token = Str::random(60) ;
                $user -> update();
                
                Mail::to($request->email)
                    ->send( new MobilePasswordResetRequest($user) );

                return response()->json([
                    'record' => $user ,
                    'ok' => true ,
                    'message' => 'យើងបានបញ្ជូនព័ត៌មាន សម្រាប់អ្នកធ្វើការកំណត់ពាក្យសម្ងាត់ឡើងវិញ ទៅ អ៊ីមែលរបស់អ្នក រួចរាល់ ! សូមពិនិត្យអ៊ីមែលរបស់អ្នកសម្រាប់ការងារបន្ត !'
                ], 200);
            }else{
                return response()->json([
                    'ok' => false ,
                    'message' => 'អ៊ីមែលនេះមិនទាន់ក្នុងប្រព័ន្ធឡើយ !'
                ], 404);
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => 'សូមបញ្ជាក់អំពី អ៊ីមែលរបស់អ្នក !'
        ], 422);
    }
    public function checkConfirmationCode(Request $request){
        if( $request->email != "" && $request->code != "" ){
            $user = \App\Models\User::where( 'email',$request->email )->where('forgot_password_token', $request->code )->first();
            return $user ;
            if ($user) {
                $user -> forgot_password_token = '' ;
                $user -> update();
                return response()->json([
                    'record' => $user ,
                    'ok' => true ,
                    'message' => 'ការបញ្ជាក់ កូដសម្ងាត់បានជោគជ័យ ! សូមបញ្ចូល ពាក្យសម្ងាត់ថ្មី របស់អ្នក !'
                ], 200);
            }else{
                return response()->json([
                    'ok' => false ,
                    'message' => 'បរាជ័យក្នុងការបញ្ជាក់ពាក្យសម្ងាត់ !'
                ], 404);
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => 'សូមបញ្ជាក់អំពី អ៊ីមែល ឬ កូដផ្លាស់ប្ដូរសម្ងាត់ របស់អ្នក !'
        ], 422);
    }
    public function passwordReset(Request $request){
        
        $record = \App\Models\User::where('email',$request->email)->first();
        if( $record ){
            $record->password = Hash::make($request->password);
            $record->update();
            return response([
                'record' => $record ,
                'ok' => true ,
                'message' => 'ផ្លាស់ប្ដូរពាក្យសម្ងាត់ថ្មីបានជោគជ័យ !'
            ],200);
        }else{
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'មានបញ្ហា អ៊ីមែល មិនត្រឹមត្រូវ សូមធ្វើការកណត់ឡើងវិញម្ដងទៀត !'
            ],201);
        }
        // 'password' => bcrypt($request->password),
    }
    public function passwordChange(Request $request){
        $record = \App\Models\User::find($request->id);
        if( $record ){
            $record->password = Hash::make($request->password);
            $record->update();
            return response([
                'record' => $record ,
                'ok' => true ,
                'message' => 'ផ្លាស់ប្ដូរពាក្យសម្ងាត់ថ្មីបានជោគជ័យ !'
            ],200);
        }else{
            return response([
                'record' => null ,
                'ok' => false ,
                'message' => 'មានបញ្ហា គណនីដែលអ្នកចង់ប្ដូរពាក្យសម្ងាត់មិនមានឡើយ !'
            ],201);
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
        if( $record->avatar_url != null && $record->avatar_url != "" && Storage::disk('public')->exists( $record->avatar_url )  ){
            $record->avatar_url = Storage::disk("public")->url( $record->avatar_url  );
        }else{
            $record->avatar_url = null ;
        }

        $record->person ;

        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
        ],200);
    }

    public function checkIdentification(Request $request){
        if( !isset( $request->term ) || strlen( trim ( $request->term ) ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីព័ត៌មានដែលត្រូវពិនិត្យផ្ទៀងផ្ទាត់។'
            ],422);
        }
        if( !isset( $request->type ) || in_array( $request->type , [ 'id' , 'phone' , 'email' ]) == false ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបំពេញព័ត៌មានឲ្យបានគ្រប់គ្រាន់។'
            ],422);
        }

        $result = null ;
        switch( $request->type ){
            case "phone" :
                $result = RecordModel::where( 'phone' , $request->term );
                break;
            case "email" :
                $result = RecordModel::where( 'email' , $request->term );
                break;
            case "id" :
                $result = RecordModel::where( 'id' , intval( $request->term ) );
                break;
        }
        /**
         * Check whether the matched case are many records
         */
        if( $result->count() > 1 ){
            return response()->json([
                'ok' => false ,
                'message' => 'ករណីផ្ទៀងផ្ទាត់ហាក់មានចំនួនច្រើន។'
            ],403);
        }
        $record = $result->first();
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានព័ត៌មានផ្ទៀងផ្ទាត់ឡើយ។'
            ],403);
        }

        // if( $record->people_id <= 0 || $record->people_id == null ){
        //     /**
        //      * Create owner of the account, in case the account does not has the owner.
        //      */
        //     $person = \App\Models\People\People::create([
        //         'firstname' => $record->firstname , 
        //         'lastname' => $record->lastname , 
        //         'gender' => $record->gender , 
        //         'dob' => $record->dob , 
        //         'mobile_phone' => $record->phone , 
        //         'email' => $record->email , 
        //         'image' => $record->avatar_url 
        //     ]);
        //     $record->people_id = $person->id ;
        //     $record->save();
        // }

        $record->officer;
        $record->officer->countesy;
        $record->officer->position;
        $record->officer->organization;
        $record->officer->people;
        $record->officer->image = $record->officer->image != null && trim($record->officer->image ) != "" && \Storage::disk('public')->exists( $record->officer->image )
            ? \Storage::disk('public')->url( $record->officer->image )
            : (
                $record->avatar_url != null && trim($record->avatar_url) != "" && \Storage::disk('public')->exists( $record->avatar_url )
                ? \Storage::disk('public')->url( $record->avatar_url )
                : false
            );

        return response()->json([
            'record' => [
                'officer' => $record->officer ,
                'id' => $record->id ,
                'phone' => $record->phone ,
                'email' => $record->email ,
                'firstname' => $record->firstname ,
                'lastname' => $record->lastname ,
                'avatar_url' => 
                    $record->avatar_url != null && strlen( $record->avatar_url ) > 0 && \Storage::disk('public')->exists( $record->avatar_url ) 
                        ? \Storage::disk('public')->url( $record->avatar_url ) 
                        : (
                            $record->person != null && $record->person->image != null && \Storage::disk('public')->exists( $record->person->image )
                                ? \Storage::disk('public')->url( $record->person->image )
                                : null
                        )
            ],
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    /**
     * Check the username
     */
    public function checkUsername(Request $request){
        if( isset( $request->username ) && $request->username != "" ){
            if( ($user = \App\Models\User::where('username',$request->username)->first() ) !== null ){
                // Username does exists
                return response([
                    'user' => $user ,
                    'ok' => true ,
                    'message' => 'ឈ្មោះនេះ មានរួចហើយ។' 
                    ],
                    200
                );
            }else{
                // User does not exists
                return response([
                    'user' => null ,
                    'ok' => false ,
                    'message' => 'ឈ្មោះនេះ មិនទានមានទេ។' 
                    ],
                    200
                );
            }
        }else{
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់ឈ្មោះប្រើប្រាស់ username។' 
                ],
                403
            );
        }
    }
    /**
     * Check the phone
     */
    public function checkPhone(Request $request){
        if( isset( $request->phone ) && $request->phone != "" ){
            if( ($user = \App\Models\User::where('phone',$request->phone)->first() ) !== null ){
                // Username does exists
                return response([
                    'user' => $user ,
                    'ok' => true ,
                    'message' => 'លេខទូរស័ព្ទនេះ មានរួចហើយ។' 
                    ],
                    200
                );
            }else{
                // User does not exists
                return response([
                    'user' => null ,
                    'ok' => false ,
                    'message' => 'លេខទូរស័ព្ទនេះ មិនទានមានទេ។' 
                    ],
                    200
                );
            }
        }else{
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់លេខទូរស័ព្ទ។' 
                ],
                403
            );
        }
    }
    /**
     * Check the email
     */
    public function checkEmail(Request $request){
        if( isset( $request->email ) && $request->email != "" ){
            if( ($user = \App\Models\User::where('email',$request->email)->first() ) !== null ){
                // Username does exists
                return response([
                    'user' => $user ,
                    'ok' => true ,
                    'message' => 'អ៊ីមែលនេះ មានរួចហើយ។' 
                    ],
                    200
                );
            }else{
                // User does not exists
                return response([
                    'user' => null ,
                    'ok' => false ,
                    'message' => 'អ៊ីមែលនេះ មិនទានមានទេ។' 
                    ],
                    200
                );
            }
        }else{
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អ៊ីមែល។' 
                ],
                403
            );
        }
    }
    public function upload(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( isset( $_FILES['files']['tmp_name'] ) && $_FILES['files']['tmp_name'] != "" ) {
                if( ( $user = RecordModel::find($request->id) ) !== null ){
                    $uniqeName = Storage::disk('public')->putFile( 'avatars/'.$user->id , new File( $_FILES['files']['tmp_name'] ) );
                    $user->avatar_url = $uniqeName ;
                    $user->save();
                    if( Storage::disk('public')->exists( $user->avatar_url ) ){
                        $user->avatar_url = Storage::disk('public')->url( $user->avatar_url  );
                        return response([
                            'record' => $user ,
                            'message' => 'ជោគជ័យក្នុងការបញ្ចូលរូបថត។'
                        ],200);
                    }else{
                        return response([
                            'record' => $user ,
                            'message' => 'គណនីនេះមិនមានរូបថតឡើយ។'
                        ],403);
                    }
                }else{
                    return response([
                        'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់គណនី។'
                    ],403);
                }
            }else{
                return response([
                    'result' => $_FILES ,
                    'message' => 'មានបញ្ហាជាមួយរូបភាពដែលអ្នកបញ្ជូនមក។'
                ],403);
            }
            
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function updateUserProfile(Request $request){
        $user = intval( $request->id ) > 0 ? \App\Models\User::find( intval( $request->id ) ) : null ;
        if( $user != null ){
            if( isset( $_FILES['files']['tmp_name'] ) && $_FILES['files']['tmp_name'] != "" ) {
                if( ( $user = RecordModel::find($request->id) ) !== null ){
                    $uniqeName = Storage::disk('public')->putFile( 'avatars/'.$user->id , new File( $_FILES['files']['tmp_name'] ) );
                    $user->avatar_url = $uniqeName ;
                    $user->save();
                    if( Storage::disk('public')->exists( $user->avatar_url ) ){
                        $user->avatar_url = Storage::disk('public')->url( $user->avatar_url  );
                        return response([
                            'record' => $user ,
                            'message' => 'ជោគជ័យក្នុងការបញ្ចូលរូបថត។'
                        ],200);
                    }else{
                        return response([
                            'record' => $user ,
                            'message' => 'គណនីនេះមិនមានរូបថតឡើយ។'
                        ],403);
                    }
                }else{
                    return response([
                        'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់គណនី។'
                    ],403);
                }
            }else{
                return response([
                    'result' => $_FILES ,
                    'message' => 'មានបញ្ហាជាមួយរូបភាពដែលអ្នកបញ្ជូនមក។'
                ],403);
            }
            
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
}
