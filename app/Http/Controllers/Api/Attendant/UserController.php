<?php
namespace App\Http\Controllers\Api\Attendant;

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
}
