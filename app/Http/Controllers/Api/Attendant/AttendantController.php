<?php

namespace App\Http\Controllers\Api\Attendant;

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
                'day_of_week' => \Carbon\Carbon::parse( $att->date )->dayOfWeek ,
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
        $responseData['timeslots'] = $user->timeslots ;

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

        if( !$timeslot ){
            return response()->json([
                "ok" => false ,
                "message" => "មិនបានបញ្ជាក់អំពី វេនធ្វើការ។"
            ],500);
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
                'updated_at' => $datetime
            ]);
        }

        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot->id ,
            'date' => $datetime->format('Y-m-d') ,
            'checktime' => $datetime->format('H:i') ,
            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_DEFAULT ,
            'check_status' => \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN ,
            'meta' => $request->meta ,
            'created_at' => $datetime ,
            'updated_at' => $datetime
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
        $timeslot = intval( $request->timeslot_id ) > 0 ? $user->timeslots()->find( $request->timeslot_id ) : \App\Models\Attendant\Timeslot::getTimeslot( $datetime ) ;

        if( !$timeslot ){
            return response()->json([
                "ok" => false ,
                "message" => "មិនបានបញ្ជាក់អំពី វេនធ្វើការ។"
            ],500);
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
                'updated_at' => $datetime
            ]);
        }

        $checktimeIn = \App\Models\Attendant\AttendantChecktime::find( $request->checktime_id );

        if( $checktimeIn == null ){
            return response()->json([
                "ok" => false ,
                "message" => "អ្នកមិនទាន់បានចូលធ្វើការនៅឡើយ។"
            ],500);
        }

        /**
         * Create checktime of the attendant
         */
        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            'timeslot_id' => $timeslot->id ,
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

}
