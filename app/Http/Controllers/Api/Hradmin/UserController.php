<?php
namespace App\Http\Controllers\Api\Hradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\client\PasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
        'people_id'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
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
                    'firstname', 'lastname', 'email', 'username' , 'phone' ,
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
            "person" => ['id','firstname' , 'lastname' , 'gender' , 'dob' , 'pob' , 'picture' ] ,
            "roles" => ['id','name', 'tag'] ,
            /**
             * Useful document to add the right to read
             */
            'regulators' => [ 'id' , 'fid', 'title', 'objective', 'year']
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        $responseData = $crud->pagination(true, $builder);
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
                )],201
            );
        }else{
            // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
            $user = new \App\Models\User([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'active' => $request->active == true || $request->active == 1 ? 1 : 0 ,
                'password' => bcrypt($request->password)
            ]);
            $user->save();

            /**
             * Assign role to user
             */
            $backendMemberRole = \App\Models\Role::where('name','backend')->where('tag','core_service')->first();
            if( $backendMemberRole != null ){
                $user->assignRole( $backendMemberRole );
            }
            
            if( $user ){
                
                return response()->json([
                    'user' => \App\Models\User::find( $user->id ) ,
                    'message' => 'គណនីបង្កើតបានជោគជ័យ !'
                ], 200);

            }else {
                return response()->json([
                    'user' => null ,
                    'message' => 'បរាជ័យក្នុងការបង្កើតគណនី !'
                ], 201);
            }
        }
    }
    /**
     * Create an account
     */
    public function update(Request $request){
        $user = isset( $request->id ) && $request->id > 0 ? \App\Models\User::find($request->id) : (
            isset( $request->email ) && $request->email != "" ? \App\Models\User::where('email',$request->email)->first() : null
        );
        if( $user ){
            // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
            $user->firstname = $request->firstname ;
            $user->lastname = $request->lastname;
            $user->email = $request->email ;
            $user->active = $request->active == true || $request->active == 1 ? 1 : 0 ;
            $user->save();    
            return response()->json([
                'user' => $user ,
                'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                'ok' => true
            ], 200);
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'user' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
                'ok' => false
                ], 201);
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
                $user -> forgot_password_token = strtoupper( Str::random(6) ) ;
                $user -> update();
                
                $user->notify( new PasswordResetRequest() );

                return response()->json([
                    'record' => $user ,
                    'ok' => true ,
                    'message' => 'យើងបានបញ្ជូនព័ត៌មាន សម្រាប់អ្នកធ្វើការកំណត់ពាក្យសម្ងាត់ឡើងវិញ ទៅ អ៊ីមែលរបស់អ្នក រួចរាល់ ! សូមពិនិត្យអ៊ីមែលរបស់អ្នកសម្រាប់ការងារបន្ត !'
                ], 200);
            }else{
                return response()->json([
                    'ok' => false ,
                    'message' => 'អ៊ីមែលនេះមិនទាន់ក្នុងប្រព័ន្ធឡើយ !'
                ], 201);
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
                ], 403);
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => 'សូមបញ្ជាក់អំពី អ៊ីមែល ឬ លេខកូដសម្រាប់ផ្លាស់ប្ដូរសម្ងាត់ របស់អ្នក !'
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
        $user = \Auth::user();
        if( $user ){
            if( !isset( $request->password ) || strlen( $request->password ) <= 0 ){
                return response([
                    'record' => $request->input() ,
                    'ok' => false ,
                    'message' => 'សូមបញ្ចូលពាក្យសម្ងាត់ !'
                ],500);
            }
            $user->password = Hash::make($request->password);
            $user->update();
            return response([
                'record' => $user ,
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
    /**
     * Check the username
     */
    public function checkUsername(Request $request){
        if( isset( $request->username ) && $request->username != "" ){
            if( ($user = \App\Models\User::where('username',strtolower( $request->username ) )->first() ) !== null ){
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
            if( ($user = \App\Models\User::where('email',strtolower( $request->email ) )->first() ) !== null ){
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
    public function officerUpdate(Request $request){
        $user = isset( $request->user_id ) && $request->user_id > 0 ? \App\Models\User::find($request->user_id) : null ;
        if( $user ){
            if( $user->officer != null && $user->officer->id == $request->officer_id ){
                $officer = \App\Models\Officer\Officer::find($request->officer_id );
                $officer == null ? false : $officer->update([
                    'code' => $request->officer_code ,
                    'phone' => $request->officer_phone ,
                    'email' => $request->officer_email ,
                    'passport' => $request->officer_passport ,
                    'date' => $request->officer_date ,
                    'organization_id' => $request->officer_organization_id ,
                    'position_id' => $request->officer_position_id ,
                    'countesy_id' => $request->officer_countesy_id ,
                ]);
                $user = \App\Models\User::find( $user->id );
                $user->people ;
                $user->card ;
                $user->officer ;
                if( $user->avatar_url !== null && Storage::disk('public')->exists( $user->avatar_url ) ){
                    $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
                }
                return response()->json([
                    'user' => [
                        'id' => $user->id ,
                        'avatar_url' => $user->avatar_url ,
                        'email' => $user->email ,
                        'firstname' => $user->firstname ,
                        'lastname' => $user->lastname ,
                        'last_login' => $user->last_login ,
                        'last_logout' => $user->last_logout ,
                        'avatar_url' => $user->avatar_url ,
                        'phone' => $user->phone ,
                        'username' => $user->username ,
                        'last_login' => $user->last_login ,
                        'roles' => $user->roles->map(function($role){
                            return collect( $role->toArray() )->only([ 'id' , 'name' , 'guard_name' , 'tag' ] );
                        }) ,
                        'officer' => $user->officer != null 
                            ? collect( $user->officer->toArray() )->only([ 'id' , 'code' , 'leader' , 'official_date' , 'passport' , 'email' , 'phone' , 'image' , 'countesy_id' , 'organization_id' , 'position_id' ])
                            : null  ,
                        'people' => $user->officer != null 
                            ? (
                                $user->officer->people != null 
                                ? collect( $user->officer->people->toArray() )->only([ 'id', 'firstname' , 'lastname' , 'enfirstname' ,'enlastname' , 'gender' , 'dob' ,'mobile_phone' , 'office_phone' , 'email', 'nid' , 'image' , 'marry_status' , 'father' , 'mother' , 'address' , 'pob' , 'passport']
                                )
                                : null 
                            )
                            : null ,
                        'card' => $user->officer != null 
                            ? (
                                $user->officer->card != null 
                                ? collect( $user->officer->card->toArray() )->only(
                                    [ 'id' , 'number' , 'uuid' , 'created_at' ]
                                )
                                : null 
                            )
                            : null ,
                        'countesy' => $user->officer != null 
                            ? (
                                $user->officer->countesy != null 
                                ? [
                                    'id' => $user->officer->countesy->id ,
                                    'name' => $user->officer->countesy->name ,
                                    'desp' => $user->officer->countesy->desp
                                ]
                                : null 
                            )
                            : null ,
                        'position' => $user->officer != null 
                            ? (
                                $user->officer->position != null 
                                ? [
                                    'id' => $user->officer->position->id ,
                                    'name' => $user->officer->position->name ,
                                    'desp' => $user->officer->position->deap ,
                                    'prefix' =>$user->officer->position->prefix
                                ]
                                : null 
                            )
                            : null ,
                        'organization' => $user->officer != null 
                            ? (
                                $user->officer->organization != null 
                                ? [
                                    'id' => $user->officer->organization->id ,
                                    'name' => $user->officer->organization->name ,
                                    'desp' => $user->officer->organization->desp ,
                                    'prefix' =>$user->officer->organization->prefix
                                ]
                                : null 
                            )
                            : null ,
                    ] ,
                    'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                    'ok' => true
                ], 200);
            }else{
                // អ្នកប្រើប្រាស់មិនមាន
                return response([
                    'user' => null ,
                    'message' => 'ព័ត៌មានអង្គភាព មន្ត្រីនេះមិនមានឡើយ។' ,
                    'ok' => false
                ], 500);
            }
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'user' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
                'ok' => false
                ], 500);
        }
    }
    public function peopleUpdate(Request $request){
        $user = intval( $request->user_id ) > 0 ? \App\Models\User::find($request->user_id) : null ;
        if( $user ){
            if( $user->officer != null && $user->officer->id == $request->officer_id ){
                if( $user->officer->people != null && $user->officer->people->id == $request->people_id ){
                    $people = \App\Models\People\People::find( $request->people_id );
                    $people == null ? false : $people->update([
                        "lastname" => $request->lastname ,
                        "firstname" => $request->firstname ,
                        "enlastname" => $request->enlastname ,
                        "enfirstname" => $request->enfirstname ,
                        "dob" => $request->dob ,
                        "gender" => $request->gender ,
                        "marry_status" => $request->marry_status ,
                        "mobile_phone" => $request->mobile_phone ,
                        "office_phone" => $request->office_phone ,
                        "email" => $request->email ,
                        "nid" => $request->nid ,
                        "passport" => $request->passport ,
                        "address" => $request->address ,
                        "pob" => $request->pob
                    ]);
                }
                $user = \App\Models\User::find( $user->id );
                $user->people ;
                $user->card ;
                $user->officer ;
                if( $user->avatar_url !== null && Storage::disk('public')->exists( $user->avatar_url ) ){
                    $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
                }
                return response()->json([
                    'user' => [
                        'id' => $user->id ,
                        'avatar_url' => $user->avatar_url ,
                        'email' => $user->email ,
                        'firstname' => $user->firstname ,
                        'lastname' => $user->lastname ,
                        'last_login' => $user->last_login ,
                        'last_logout' => $user->last_logout ,
                        'avatar_url' => $user->avatar_url ,
                        'phone' => $user->phone ,
                        'username' => $user->username ,
                        'last_login' => $user->last_login ,
                        'roles' => $user->roles->map(function($role){
                            return collect( $role->toArray() )->only([ 'id' , 'name' , 'guard_name' , 'tag' ] );
                        }) ,
                        'officer' => $user->officer != null 
                            ? collect( $user->officer->toArray() )->only([ 'id' , 'code' , 'leader' , 'official_date' , 'passport' , 'email' , 'phone' , 'image' , 'countesy_id' , 'organization_id' , 'position_id' ])
                            : null  ,
                        'people' => $user->officer != null 
                            ? (
                                $user->officer->people != null 
                                ? collect( $user->officer->people->toArray() )->only([ 'id', 'firstname' , 'lastname' , 'enfirstname' ,'enlastname' , 'gender' , 'dob' ,'mobile_phone' , 'office_phone' , 'email', 'nid' , 'image' , 'marry_status' , 'father' , 'mother' , 'address' , 'pob' , 'passport' ]
                                )
                                : null 
                            )
                            : null ,
                        'card' => $user->officer != null 
                            ? (
                                $user->officer->card != null 
                                ? collect( $user->officer->card->toArray() )->only(
                                    [ 'id' , 'number' , 'uuid' , 'created_at' ]
                                )
                                : null 
                            )
                            : null ,
                        'countesy' => $user->officer != null 
                            ? (
                                $user->officer->countesy != null 
                                ? [
                                    'id' => $user->officer->countesy->id ,
                                    'name' => $user->officer->countesy->name ,
                                    'desp' => $user->officer->countesy->desp
                                ]
                                : null 
                            )
                            : null ,
                        'position' => $user->officer != null 
                            ? (
                                $user->officer->position != null 
                                ? [
                                    'id' => $user->officer->position->id ,
                                    'name' => $user->officer->position->name ,
                                    'desp' => $user->officer->position->deap ,
                                    'prefix' =>$user->officer->position->prefix
                                ]
                                : null 
                            )
                            : null ,
                        'organization' => $user->officer != null 
                            ? (
                                $user->officer->organization != null 
                                ? [
                                    'id' => $user->officer->organization->id ,
                                    'name' => $user->officer->organization->name ,
                                    'desp' => $user->officer->organization->desp ,
                                    'prefix' =>$user->officer->organization->prefix
                                ]
                                : null 
                            )
                            : null ,
                    ] ,
                    'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                    'ok' => true
                ], 200);
            }else{
                // អ្នកប្រើប្រាស់មិនមាន
                return response([
                    'user' => null ,
                    'message' => 'ព័ត៌មានផ្ទាល់ខ្លួន មន្ត្រីនេះមិនមានឡើយ។' ,
                    'ok' => false
                ], 500);
            }
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'user' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
                'ok' => false
                ], 500);
        }
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
}
