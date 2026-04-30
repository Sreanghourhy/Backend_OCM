<?php

namespace App\Http\Controllers\Api\Hradmin\Attendant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendant\Attendant as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class AttendantController extends Controller
{
    private $selectFields = [
        'id',
        'user_id' ,
        'date' ,
        'late_or_early' ,
        'duration' ,
        'worked_time' ,
        // 'overtime' ,
        'created_at' , 
        'updated_at'
    ];
        
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->search !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        /**
         * Filter conditions
         */
        $date = isset( $request->date ) & strlen( $request->date ) >= 10 ? \Carbon\Carbon::parse( $request->date ) : false ; // 2023-10-10
        $attendantType = isset( $request->attendantType ) && $request->attendantType != "" && in_array( $request->attendantType , Attendant::ATTENDANT_TYPES ) ? $request->attendantType : false ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'user_id' ,
                        'value' => $user->id
                    ],
                    $attendantType != false
                        ? [
                            'field' => 'attendant_type' ,
                            'value' => $attendantType
                        ] : []
                ],
                // 'in' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
                //     ]
                // ] ,
                // 'not' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
                //     ]
                // ] ,
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
            //     $search != false ?
            //     [
            //         "relationship" => 'staff',
            //         "where" => [
            //             // "in" => [
            //             //     "field" => "id",
            //             //     "value" => [$request->unit]
            //             // ],
            //             // "not"=> [
            //             //     [
            //             //         "field" => 'fieldName' ,
            //             //         "value"=> 'value'
            //             //     ]
            //             // ],
            //             "like"=>  [
            //                 [
            //                     "field"=> 'firstname' ,
            //                     "value"=> $search
            //                 ],
            //                 [
            //                     "field"=> 'lastname' ,
            //                     "value"=> $search
            //                 ]
            //             ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            // "search" =>
            //     $search === false ? [] : [
            //         'value' => $search ,
            //         'fields' => [
            //             'date'
            //         ] 
            //     ]
            // ,
            "order" => [
                'field' => 'date' ,
                'by' => 'desc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);

        // $crud->setRelationshipFunctions([
        //     /** relationship name => [ array of fields name to be selected ] */
        // ]);

        $builder = $crud->getListBuilder();

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        if( $date != false ){
            $builder->whereBetween('date',[ $startOfMonth->format('Y-m-d'),$endOfMonth->format('Y-m-d')]);
        }

        
        $responseData = $crud->pagination(true, $builder);
        $temp = [] ;
        foreach( $responseData['records'] AS $index => $att ){
            $att = RecordModel::find( $att['id'] );
            $temp[ $att->date ] = [
                'id' => $att -> id ,
                'calculateTime' => $att->calculateWorkingTime() ,
                'date' => $att->date ,
                'user' => [
                    'id' => $att->user->id ,
                    'firstname' => $att->user->firstname ,
                    'lastname' => $att->user->lastname
                ]
            ];
        }
        // $responseData['records'] = $responseData['records']->map(function($att){   
        //     return [
        //         'id' => $att['id'] ,
        //         'calculateTime' => RecordModel::find($att['id'])->calculateWorkingTime() ,
        //         'attendant_type' => $att['attendant_type'] ,
        //         'date' => $att['date'] ,
        //         'staff' => $att['staff'] ,
        //         'timeslots' => $att['timeslots'] ,
        //         'attendant_type' => $att['attendant_type']
        //     ];
        // });
        /**
         * Get user timeslots
         */
        $responseData['timeslots'] = $user->timeslots()->orderby('id','asc')->get()->map(function($tm){
            return [
                "id" => $tm->id ,
                "active" => $tm->active ,
                'effective_day' => $tm->effective_day ,
                'rest_duration' => $tm->rest_duration ,
                'start' => $tm->start ,
                'end' => $tm->end ,
                'title' => $tm->title ,
                'minutes' => $tm->getMinutes() ,
                'hours' => $tm->getHours()
            ];
        }) ;

        $responseData['records'] = $temp ;
        do{
            $responseData['daysOfMonth'][] = [
                'sunday' => $startOfMonth->isSunday() ,
                'date' => $startOfMonth->format('Y-m-d') ,
                'number' => $startOfMonth->dayOfWeek ,
                'timeslots' => $user->timeslots()->orderby('id','asc')->get()
            ];
            $startOfMonth->addDay();    
        }while( $startOfMonth->lte( $endOfMonth ) );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }

    /**
     * Checkin with timeslot
     */
    public function authenticatedCheckin(Request $request){
        $user = \Auth::user();
        
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមូនសិន។'
            ],403);
        }

        $datetime = \Carbon\Carbon::now();
        if( ( isset( $request->day ) && isset( $request->time ) ) && ( strlen( $request->day ) >= 6 && strlen( $request->time ) >= 4 ) ){
            $datetime = \Carbon\Carbon::parse( $request->day . ' ' . $request->time );
        }
        $timeslot = intval( $request->timeslot_id ) > 0 ? $user->timeslots()->find( $request->timeslot_id ) : \App\Models\Attendant\Timeslot::getTimeslot( $datetime ) ;

        // if( !$timeslot ){
        //     return response()->json([
        //         "ok" => false ,
        //         "message" => "មិនបានបញ្ជាក់អំពី វេនធ្វើការ។"
        //     ],500);
        // }

        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$datetime->format('Y-m-d'))->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $datetime->format('Y-m-d') ,
                'created_at' => $datetime ,
                'updated_at' => $datetime ,
                'attendant_type' => "P"
            ]);
        }

        /**
         * Create checktime of the attendant
         */
        $parentChecktime = $attendant->checktimes->count() > 0 
            ? $attendant->checkTimes()->orderby('id','desc')->first()
            : null ;

        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot != null ? $timeslot->id : 0 ,
            'date' => $datetime->format('Y-m-d') ,
            'checktime' => $datetime->format('H:i') ,
            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_DEFAULT ,
            'check_status' => $parentChecktime == null 
                ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN 
                : (
                    $parentChecktime->check_status == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN
                    ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT
                    : \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN
                ) ,
            'meta' => $request->meta ,
            'created_at' => $datetime ,
            'updated_at' => $datetime ,
            'parent_checktime_id' => $parentChecktime == null ? 0 : $parentChecktime->id
        ]);

        return response()->json([
            'ok' => true ,
            'user' => \Auth::user() ,
            'attendant' => $attendant ,
            'timeslot' => $timeslot ,
            'checktime' => $checktime 
        ],200);
    }
    
    /**
     * Checkout with timeslot
     */
    public function authenticatedCheckout(Request $request){
        $user = \Auth::user();
        
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមូនសិន។'
            ],403);
        }

        $datetime = \Carbon\Carbon::now();
        if( ( isset( $request->day ) && isset( $request->time ) ) && ( strlen( $request->day ) >= 6 && strlen( $request->time ) >= 4 ) ){
            $datetime = \Carbon\Carbon::parse( $request->day . ' ' . $request->time );
        }

        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$datetime->format('Y-m-d'))->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $datetime->format('Y-m-d') ,
                'created_at' => $datetime ,
                'updated_at' => $datetime ,
                'attendant_type' => "P"
            ]);
        }

        $checktimeIn = \App\Models\Attendant\AttendantCheckTime::find( $request->checktime_id );

        if( $checktimeIn == null ){
            return response()->json([
                "ok" => false ,
                "message" => "អ្នកមិនទាន់បានចូលធ្វើការនៅឡើយ។"
            ],500);
        }

        $timeslot = intval( $request->timeslot_id ) > 0 ? $user->timeslots()->find( $request->timeslot_id ) : \App\Models\Attendant\Timeslot::getTimeslot( $datetime ) ;

        // Allow to checkout without timeslot
        // if( !$timeslot ){
        //     return response()->json([
        //         "ok" => false ,
        //         "message" => "មិនបានបញ្ជាក់អំពី វេនធ្វើការ។"
        //     ],500);
        // }

        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot == null ? 0 : $timeslot->id ,
            'date' => $datetime->format('Y-m-d') ,
            'checktime' => $datetime->format('H:i') ,
            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_DEFAULT ,
            'check_status' => \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT ,
            'meta' => $request->meta ,
            'parent_checktime_id' => $checktimeIn->id ,
            'created_at' => $datetime ,
            'updated_at' => $datetime
        ]);

        /**
         * Update the working hours in minutes into attendant
         */
        $attendant->updateWorkingMinutes();

        return response()->json([
            'ok' => true ,
            'user' => \Auth::user() ,
            'attendant' => $attendant ,
            'timeslot' => $timeslot ,
            'checktime' => $checktime 
        ],200);
    }

    /**
     * Check in and out with attendant without specified the timeslot.
     * Required information : user (UserId) , check statye (IN , OUT)
     * 
     */
    public function systemCheckAttendant(Request $request){

        $user = \Auth::user();

        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមូនសិន។'
            ],403);
        }

        $now = \Carbon\Carbon::now();

        /**
         * Check whether the user has timtslots
         */
        $timeslots = $user->timeslots;
        if( $timeslots == null || $timeslots->count <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => "មិនទាន់មានម៉ោងធ្វើការនៅឡើយ។"
            ],500);
        }

        /**
         * Check the checktime off the timeslot
         */

        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$now->format('Y-m-d'))->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $date->format('Y-m-d') ,
                'worked_hours' => 0.0 ,
                'worked_minutes' => 0.0 ,
                'ot_hours' => 0.0 ,
                'ot_minutes' => 0.0 ,
                'attendant_type' => 'P' ,
                'created_at' => $now ,
                'updated_at' => $now
            ]);
        }
        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->attendantCheckTimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot->id ,
            'date' => $date->format('Y-m-d') ,
            'checktime' => $date->format('H:i') ,
            'checktype' => 'System' ,
            'check_status' => in_array( $request->check_status , Attendant::ATTENDANT_CHECK_STATUS ) ? $request->check_status : '' ,
            'meta' => $request->meta ,
            'created_at' => $now ,
            'updated_at' => $now
        ]);

        return response()->json([
            'user' => \Auth::user() ,
            'attendant' => $attendant ,
            'timeslot' => $timeslot ,
            'checktime' => $checktime 
        ],200);
    }

    /**
     * Check in and out with attendant without specified the timeslot.
     * Required information : Email or Phone of the User Account , check status (IN , OUT)
     */
    public function checkAttendantByEmailOrPhoneByOrganization(Request $request){
        $organization = null ;
        if( isset( $request->organization_id ) && strlen( trim ( $request->organization_id ) ) > 0 && intval( $request->organization_id ) > 0 ){
            $organization = \App\Models\Organization\Organization::where('id', intval( $request->organization_id ) )->first();
        }
        // return response()->json([
        //     'result' => $organization
        // ],200);
        $result = false ;
        if( isset( $request->email ) && strlen( trim ( $request->email ) ) > 0 ){
            $result = \App\Models\User::where('email',$request->email);
        }
        else if( isset( $request->phone ) && strlen( trim ( $request->phone ) ) > 0 ){
            $result = \App\Models\User::where('phone',$request->phone);
        }
        if( $result == false ){
            return response()->json([
                'ok'=> false ,
                'message' => 'សូមពិនិត្យព័ត៌មានដែលអ្នកបានផ្ដល់ម្ដងទៀត។'
            ],403);
        }
        if( $result != false && $result->count() > 1 ){
            return response()->json([
                'ok'=> false ,
                'message' => 'ព័ត៌មានដែលផ្ទៀងផ្ទាត់មានចំនួនច្រើន។' ,
                'result' => $result->get()
            ],403);
        }
        $user = $result->first();
        $now = \Carbon\Carbon::now();
        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$now->format('Y-m-d'))->orderBy('id','desc')->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $now->format('Y-m-d') ,
                'late_or_early' => 0.0 ,
                'worked_time' => 0.0 ,
                'duration' => 0.0 ,
                'attendant_type' => 'P' ,
                'created_at' => $now ,
                'updated_at' => $now
            ]);
        }
        /**
         * Create checktime of the attendant
         */
        $parentChecktime = $attendant->checktimes->count() > 0 
            ? $attendant->checkTimes()->orderby('id','desc')->first()
            : null ;

        // if the photo is provided
        $uniqeName = "" ;
        if( isset( $request->photo ) && strlen( trim ( $request->photo ) ) > 0 ){

            /**
             * Create backup folder
             */
            $folderName = \Carbon\Carbon::now()->format('Y-m-d');
            if( !\Storage::disk('attendant')->exists( $folderName ) ){
                if( ( $result = \Storage::disk('attendant')->makeDirectory( $folderName ) ) != true ){
                    return response()->json([
                        'ok' => false ,
                        'result' => $result ,
                        'message' => 'មានបញ្ហាក្នុងការបង្កើតថតដាក់រូបថត។'
                    ],403);
                }
            }

            list($base64,$data) = explode( 'base64,' , $request->photo );
            $data = base64_decode($data);
            $filename = md5( \Carbon\Carbon::now()->format('Y-m-d H:i:s')).'.jpg';
            if( Storage::disk('attendant')->put( $folderName.'/'.$filename , $data , 'public' ) == false ){
                return response()->json([
                    'ok' => false ,
                    'result' => $folderName.'/'.$filename ,
                    'message' => 'មានបញ្ហាដាក់រូបភាព។'
                ],403);
            }
            $uniqeName = $folderName.'/'.$filename ;
        }

        // $timeslot = \App\Models\Attendant\Timeslot::getTimeslot( $now ) ;
        
        // if( $timeslot == null ){
        //     return response()->json([
        //         'message' => 'គណនីនេះមិនទាន់វេនការងារនៅឡើយ។'
        //     ],503);
        // }

        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            // 'timeslot_id' => $timeslot->id ,
            'timeslot_id' => 0 ,
            'organization_id' => $organization == null ? 0 : $organization->id ,
            'checktime' => $now->format('H:i') ,
            
            // 'check_status' => $parentChecktime == null ? "IN" : "OUT" ,
            // 'checktype' => 'System' ,

            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_PHONE_EMAIL ,
            'check_status' => $parentChecktime == null 
                ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN
                : (
                    $parentChecktime->check_status == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT 
                        ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN 
                        : \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT
                ),
            'parent_checktime_id' => $parentChecktime == null ? 0 : $parentChecktime->id ,
            'meta' => $request->meta ,
            'created_at' => $now ,
            'updated_at' => $now ,
            'lat' => $request->lat ,
            'lng' => $request->lng ,
            'photo' => $uniqeName 
        ]);

        return response()->json([
            'ok' => true ,
            'message' => 'ជោគជ័យ។' ,
            'attendant' => $attendant ,
            'checktime' => $checktime 
        ],200);
    }

    /**
     * Check in and out with attendant without specified the timeslot.
     * Required information : Email or Phone of the User Account , check status (IN , OUT)
     */
    public function getAttendantByEmailOrPhone(Request $request){
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
                $result = \App\Models\User::where( 'phone' , $request->term );
                break;
            case "email" :
                $result = \App\Models\User::where( 'email' , $request->term );
                break;
            case "id" :
                $result = \App\Models\User::where( 'id' , intval( $request->term ) );
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
        $user = $result->first();
        $now = \Carbon\Carbon::now();
        if( $user == null ){
            return response()->json([
                'ok'=> false ,
                'message' => 'សូមពិនិត្យព័ត៌មានដែលអ្នកបានផ្ដល់ម្ដងទៀត។'
            ],403);
        }
        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$now->format('Y-m-d'))->orderBy('id','desc')->first() ;
        if( $attendant == null ){
            // return response()->json([
            //     'ok' => false ,
            //     'message' => 'មិនទាន់បានចុះវត្តមានឡើយ។'
            // ],403);
            return response()->json([
                'ok' => true ,
                'message' => 'មិនទាន់បានចុះវត្តមាន។' ,
                'attendant' => null ,
                'checktimes' => [] ,
                // User does not have any attendant check yet
                'check_status' => -1
            ],200);
        }

        $lastChecktime = $attendant->checktimes()->orderby('id','desc')->first();

        $checkStatus = $lastChecktime != null
            ? $lastChecktime->check_status == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN : \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT 
            // User does not have any attendant check yet
            : -1 ;

        return response()->json([
            'ok' => true ,
            'message' => 'បានចុះវត្តមានរួចហើយ។' ,
            'attendant' => $attendant ,
            'checktimes' => $attendant->checktimes()->orderby('id')->get()->each(function($ck){ $ck->organization; }) ,
            'check_status' => $checkStatus
        ],200);
    }
    public function readAttendantChecktimePhoto(Request $request){
        $checktime = intval( $request->id ) > 0 ? \App\Models\Attendant\AttendantCheckTime::find( $request->id ) : null ;
        if( $checktime != null ){
            $base64 = false ;
            if( strlen( $checktime->photo ) > 0 && \Storage::disk('attendant')->exists( $checktime->photo ) ){
                $path = storage_path('data/attendants') . '/' . $checktime->photo ;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return response()->json([
                'ok' => true , 
                'base64' => $base64 ,
                'message' => 'ជោគជ័យ'
            ],200);
        }
        return response()->json([
            'ok' => false , 
            'message' => 'មានបញ្ហាអានរូបភាព។' ,
        ],403);
    }

    /**
     * Update attendant type
     */
    /**
     * Checkout with timeslot
     */
    public function updateAttendantType(Request $request){
        $user = \Auth::user();
        
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមូនសិន។'
            ],403);
        }

        $datetime = \Carbon\Carbon::now();
        if( ( isset( $request->day ) && isset( $request->time ) ) && ( strlen( $request->day ) >= 6 && strlen( $request->time ) >= 4 ) ){
            $datetime = \Carbon\Carbon::parse( $request->day . ' ' . $request->time );
        }

        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$datetime->format('Y-m-d'))->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $datetime->format('Y-m-d') ,
                'created_at' => $datetime ,
                'updated_at' => $datetime ,
                'attendant_type' => "P"
            ]);
        }

        $checktimeIn = \App\Models\Attendant\AttendantCheckTime::find( $request->checktime_id );

        if( $checktimeIn == null ){
            return response()->json([
                "ok" => false ,
                "message" => "អ្នកមិនទាន់បានចូលធ្វើការនៅឡើយ។"
            ],500);
        }

        $timeslot = intval( $request->timeslot_id ) > 0 ? $user->timeslots()->find( $request->timeslot_id ) : \App\Models\Attendant\Timeslot::getTimeslot( $datetime ) ;

        // Allow to checkout without timeslot
        // if( !$timeslot ){
        //     return response()->json([
        //         "ok" => false ,
        //         "message" => "មិនបានបញ្ជាក់អំពី វេនធ្វើការ។"
        //     ],500);
        // }

        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot == null ? 0 : $timeslot->id ,
            'date' => $datetime->format('Y-m-d') ,
            'checktime' => $datetime->format('H:i') ,
            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_DEFAULT ,
            'check_status' => \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT ,
            'meta' => $request->meta ,
            'parent_checktime_id' => $checktimeIn->id ,
            'created_at' => $datetime ,
            'updated_at' => $datetime
        ]);

        /**
         * Update the working hours in minutes into attendant
         */
        $attendant->updateWorkingMinutes();

        return response()->json([
            'ok' => true ,
            'user' => \Auth::user() ,
            'attendant' => $attendant ,
            'timeslot' => $timeslot ,
            'checktime' => $checktime 
        ],200);
    }
}
