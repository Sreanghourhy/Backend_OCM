<?php
namespace App\Http\Controllers\Api\AuthenticationCenter\Meeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting\Meeting as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Regulator\LegalDraft;

class MeetingController extends Controller
{
    private $selectFields = [
        'id',
        'active' ,
        'objective' ,
        'date' ,
        'start',
        'end' ,
        'actual_start' ,
        'actual_end' ,
        'timestamp_start' ,
        'timestamp_end' ,
        'timestamp_actual_start' ,
        'timestamp_actual_end' ,
        'status' ,
        'seichdey_preeng' ,
        'minister_preeng' ,
        'reports' , 
        'tech_reports' ,
        'other_documents' ,
        'contact_info' ,
        'pid' ,
        'type_id' ,
        'created_by' ,
        'updated_by' ,
        'summary'
    ];
        
    /**
     * Listing function
     */
    public function index(Request $request){

        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $date = isset( $request->date ) && strlen( $request->date ) > 0 ? \Carbon\Carbon::parse( $request->date ) : false ;

        $queryString = [
            "where" => [
                'default' => [
                    $date == false ? [] : [
                        'field' => 'date' ,
                        'value' => $date->format('Y-m-d')
                    ]
                ],
                // 'in' => [
                //     $user != null && $user->id > 0 
                //     ? [
                //         'field' => 'created_by' ,
                //         'value' => [ $user->id ]
                //     ] 
                //     : []
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
                    'objective', 'date' ,'summary'
                ]
            ],
            "order" => [
                // 'field' => 'id' ,
                // 'by' => 'desc'
                [
                    'field' => 'date' ,
                    'by' => 'asc'
                ] ,
                [
                    'field' => 'start' ,
                    'by' => 'asc'
                ]
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,
            [
                /**
                 * custom the value of the field
                 */
                'minister_preeng' => function($record){
                    $preeng = [] ;
                    if( is_array( $record->minister_preeng ) && count( $record->minister_preeng ) ){
                        foreach( $record->minister_preeng AS $index => $pdf ){
                            if( $pdf['serial'] != "" && $pdf['serial'] != null && !is_array( $pdf['serial'] ) && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $preeng[] = [
                                'name' => $pdf['name'] ,
                                'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
                            ];
                        }
                    }
                    return $preeng ;
                },
                'seichdey_preeng' => function($record){
                    $preeng = [] ;
                    if( is_array( $record->seichdey_preeng ) && count( $record->seichdey_preeng ) ){
                        foreach( $record->seichdey_preeng AS $index => $pdf ){
                            if( $pdf['serial'] != "" && $pdf['serial'] != null && !is_array( $pdf['serial'] ) && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $preeng[] = [
                                'name' => $pdf['name'] ,
                                'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
                            ];
                        }
                    }
                    return $preeng ;
                },
                'reports' => function($record){
                    $reports = [] ;
                    if( is_array( $record->reports ) && count( $record->reports ) ){
                        foreach( $record->reports AS $index => $pdf ){
                            if( $pdf['serial'] != "" && $pdf['serial'] != null && !is_array( $pdf['serial'] ) && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $reports[] = [
                                'name' => $pdf['name'] ,
                                'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
                            ];
                        }
                    }
                    return $reports;
                },
                'tech_reports' => function($record){
                    $reports = [] ;
                    if( is_array( $record->tech_reports ) && count( $record->tech_reports ) ){
                        foreach( $record->tech_reports AS $index => $pdf ){
                            if( $pdf['serial'] != "" && $pdf['serial'] != null && !is_array( $pdf['serial'] ) && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $reports[] = [
                                'name' => $pdf['name'] ,
                                'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
                            ];
                        }
                    }
                    return $reports;
                },
                'other_documents' => function($record){
                    $documents = [] ;
                    if( is_array( $record->other_documents ) && count( $record->other_documents ) ){
                        foreach( $record->other_documents AS $index => $pdf ){
                            if( $pdf['serial'] != "" && $pdf['serial'] != null && !is_array( $pdf['serial'] ) && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $documents[] = [
                                'name' => $pdf['name'] ,
                                'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
                            ];
                        }
                    }
                    return $documents ;
                },
                'objective' => function($record){
                    return html_entity_decode( strip_tags( $record->objective ) );
                }
            ],
            false ,
            [
                'status_label' => function($record){
                    return $record->getStatusAsString();
                }
            ]
        );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'comments' => [ 'id' , 'people_id' , 'comment' ] ,
            'organizations' => [ 'id' , 'name' , 'desp' ] ,
            'rooms' => [ 'id' , 'name' , 'desp' , 'image' ] ,
            // 'listMembers' => [ 'id' , 'people_id' , 'role' , 'group' ,'remark'
            //     // Relation within listMembers
            //     , 'member' => [ 'id' , 'firstname' , 'lastname' , 'mobile_phone' , 'email' ]
            //     , 'attendant' => [ 'id' , 'checktime' , 'remark' , 'people_id' 
            //         // , 'member' => [ 
            //         //     'id' , 'firstname' , 'lastname' , 'mobile_phone' , 'email' 
            //         // ] 
            //     ]
            // ] ,
            'regulators' => [ 'id', 'fid' , 'title' , 'objective', 'year' , 'pdf' 
                // Relation with regulators
                , 'type' => [ 'id' , 'name' , 'desp' ]
            ],
            'type' => [ 'id' , 'name' , 'desp' ] ,
            'children' => [ 'id' , 'objective' , 'date' , 'start' , 'end' ] ,
            'legalDraft' => [ 'id' , 'objective' , 'status' , 'pid' ]
        ]);
        /**
         * Filter the meeting to get the top level
         */
        $builder = $crud->getListBuilder();

        // Filter by type
        $statuses = isset( $request->statuses ) && strlen( $request->statuses ) > 0 ? explode( ',' , trim( $request->statuses ) ) : [];
        $statuses = is_array( $statuses ) && !empty( $statuses )
            ? $statuses
            : false ;
        if( $statuses !== false ){
            $builder->whereIn( 'status' , $statuses );
        }

        $types = isset( $request->types ) && strlen( $request->types ) > 0 ? explode( ',' , trim( $request->types ) ) : [];
        $types = is_array( $types ) && !empty( $types )
            ? $types
            : false ;
        if( $types !== false ){
            $builder->whereIn( 'type_id' , $types );
        }

        // Filter by room
        $rooms = isset( $request->rooms ) && strlen( $request->rooms ) > 0 ? explode( ',' , trim( $request->rooms ) ) : [];
        $rooms = is_array( $rooms ) && !empty( $rooms )
            ? $rooms
            : false ;
        if( $rooms !== false ){
            $builder->whereHas( 'rooms' , function(Builder $query)use( $rooms ){
                $query->whereIn('meeting_rooms.room_id',$rooms);
            } );
        }
        
        // Filter by organizzation
        $organizations = isset( $request->organizations ) && strlen( $request->organizations ) > 0 ? explode( ',' , trim( $request->organizations ) ) : [];
        $organizations = is_array( $organizations ) && !empty( $organizations )
            ? $organizations
            : false ;
        if( $organizations !== false ){
            $builder->whereHas( 'organizations' , function(Builder $query)use( $organizations ){
                $query->whereIn('meeting_organizations.organization_id',$organizations);
            } );
        }

        // Filter by member
        $members = isset( $request->members ) && strlen( $request->members ) > 0 ? explode( ',' , trim( $request->members ) ) : [];
        $members = is_array( $members ) && !empty( $members )
            ? $members
            : false ;
        if( $members !== false ){
            $builder->whereHas( 'members' , function(Builder $query)use( $members ){
                $query->whereIn('meeting_members.people_id',$members);
            } );
        }

        $responseData = $crud->pagination(true,$builder);
        $responseData['records'] = $responseData['records']->map(function($record){
            $meetingObj = RecordModel::find( $record['id'] );
            $record['rooms'] = $record['rooms'] == null ? [] : array_map( function($room){
                $room->image = $room->image != null && strlen( $room->image ) > 0 && \Storage::disk('public')->exists( $room->image )
                    ? \Storage::disk("public")->url( $room->image  )
                    : false ;
                return $room; 
            } , $record['rooms'] );

            $record['listMembers'] = $meetingObj->listMembers()
            // ->where(
            //     [
            //         'group' => 'lead_meeting' ,
            //         'role' => 'leader'
            //     ]
            // ) 
            ->get()->map(function($meetingMember){
                $people = \App\Models\People\People::select([ 'id' , 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' ])->find( $meetingMember->people_id );
                if( $people != null ) {
                    $people->countesies;
                    $people->positions;
                    $people->officers = $people->officers()->select(['id' , 'email' , 'phone' , 'countesy_id' , 'position_id' , 'organization_id'])->get()->map(function($officer){
                        $officer->countesy;
                        $officer->position;
                        $officer->organization;
                        return $officer;
                    });
                    $people->officer;
                    $people->organizations;
                }
                $meetingMember->member = $people ;
                return $meetingMember ;
            });

            return $record ;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    public function create(Request $request){
        /**
         * Save information of the regulator and its related information
         */
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }

        // Pad string
        $date = strlen( $request->date ) >= 0 ? \Carbon\Carbon::parse( $request->date ) : \Carbon\Carbon::now() ;
        // Start time
        list($startHour,$startMinute) = explode( ":" , $request->start );
        $start = implode(':', [ str_pad( $startHour , 2, '0' , STR_PAD_LEFT ) , str_pad( $startMinute , 2, '0' , STR_PAD_LEFT ) ] ) ;
        $start = strlen( $start ) >= 5 ? $start : "09:00" ;
        // End time
        list($endHour,$endMinute) = explode( ":" , $request->end );
        $end = implode( ':' , [ str_pad( $endHour , 2, '0' , STR_PAD_LEFT ) , str_pad( $endMinute , 2, '0' , STR_PAD_LEFT ) ] );
        $end = strlen( $end ) >= 5 ? $end : "12:00" ;
        $dateStart = $date . ' ' . $start ;
        $dateEnd = $date . ' ' . $end ;
        /**
         * Create a meeting under another meeting
         */
        $parent = $legalDraft = null ;
        if( intval( $request->pid ) > 0  ){
            $parent = RecordModel::find( $request->pid ) ;
            if( $parent == null ){
                return response()->json([
                    'message' => 'រកកិច្ចប្រជុំមិនឃើញ។'
                ]
                , 500 );
            }
            // Create LegalDraft
            $legalDraft = $parent->legalDraft;
        }else{
            // Create LegalDraft
            $legalDraft = LegalDraft::create([
                'title' => $request->title?? '' ,
                'objective' => $request->objective?? '' ,
                'content' => $request->content?? '' ,
                'created_by' => $user->id ,
                'updated_by' => $user->id ,
                'status' => 0
            ]);
        }

        $record = RecordModel::create([
            'legal_draft_id' => $legalDraft->id ,
            'objective' => $request->objective?? '',
            'summary' => $request->summary??'' ,
            'date' => $date ,
            'start' => $start ,
            'end' =>  $end ,
            'timestamp_start' => $dateStart ,
            'timestamp_end' => $dateEnd ,
            'status' => \App\Models\Meeting\Meeting::STATUS_NEW ,
            'contact_info' => $request->contact_info?? '' ,
            'created_by' => $user->id  ,
            'updated_by' => $user->id  ,
            'type_id' => is_array( $request->type_id ) ? $request->type_id[0] : $request->type_id ,
            'pid' => $parent != null && $parent->id > 0 
                ? (
                    // In case the parent already has its parent
                    $parent->pid > 0 
                    // Then choose the top parent as the parent
                    ? $parent->pid
                    // But if the parent does not has the parent the choose its id as parent
                    : $parent->id
                ) 
                : 0
        ]);
        /**
         * Reference files
         * 'seichdey_preeng' => $request->number?? '' ,
         * 'reports' => $request->number?? '' , 
         * 'other_documents' => $request->number?? '' ,
         */

        /**
         * Sync the pivots
         */
        $organizations = isset( $request->organizations ) && is_array( $request->organizations ) && count( $request->organizations ) 
            ? $request->organizations
            : explode( ',' , $request->organizations );
        if( count( $organizations ) > 0 ){
            $record->organizations()->sync( $request->organizations );
        }
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        if( intval( $request->id ) > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            // Pad string
            $date = strlen( $request->date ) >= 0 ? \Carbon\Carbon::parse( $request->date ) : \Carbon\Carbon::now() ;
            // Start time
            list($startHour,$startMinute) = explode( ":" , $request->start );
            $start = implode(':', [ str_pad( $startHour , 2, '0' , STR_PAD_LEFT ) , str_pad( $startMinute , 2, '0' , STR_PAD_LEFT ) ] ) ;
            $start = strlen( $start ) >= 5 ? $start : "09:00" ;
            // End time
            list($endHour,$endMinute) = explode( ":" , $request->end );
            $end = implode( ':' , [ str_pad( $endHour , 2, '0' , STR_PAD_LEFT ) , str_pad( $endMinute , 2, '0' , STR_PAD_LEFT ) ] );
            $end = strlen( $end ) >= 5 ? $end : "12:00" ;
            $dateStart = $date . ' ' . $start ;
            $dateEnd = $date . ' ' . $end ;
            /**
             * Save information of the regulator and its related information
             */
            if( $record->update([
                'objective' => $request->objective?? '',
                'summary' => $request->summary?? '',
                'date' => $date ,
                'start' => $start ,
                'end' => $end ,
                'timestamp_start' => $dateStart ,
                'timestamp_end' => $dateEnd ,
                'contact_info' => $request->contact_info?? '' ,
                'updated_by' => $user->id  ,
                'type_id' => $request->type_id ,
                'status' => $request->status > 0 ? $request->status : $record->status
            ]) ){

                /**
                 * Sync the pivots
                 */
                $organizations = isset( $request->organizations ) && is_array( $request->organizations ) && count( $request->organizations ) 
                    ? $request->organizations
                    : explode( ',' , $request->organizations );
                if( count( $organizations ) > 0 ){
                    $record->organizations()->sync( $request->organizations );
                }

                $responseData['message'] = __("crud.read.success");
                $responseData['ok'] = true ;
                $responseData['record'] = $record ;
                return response()->json($responseData, 200);
            }else{
                return response()->json([
                    'message' => 'មានបញ្ហាក្នុងការរក្សារព័ត៌មានប្រជុំ។'
                ], 403);    
            }
        }else{
            return response()->json([
                'input' => $request->id ,
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ប្រជុំ។'
            ], 403);
        }
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
                'message' => 'ទិន្នន័យដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }

        $record->updateStatus();
        $record->createdBy ;
        $record->updatedBy ;
        $record->seichdey_preeng = collect( $record->seichdey_preeng )->map(function($preeng){
            return strlen($preeng) && Storage::disk('meeting')->exists( $preeng ) ? Storage::disk("meeting")->url( $preeng ) : false ;
        });
        $record->reports = collect( $record->reports )->map(function($report){
            return strlen($report) && Storage::disk('meeting')->exists( $report ) ? Storage::disk("meeting")->url( $report ) : false ;
        });
        $record->other_documents = collect( $record->other_documents )->map(function($other){
            return strlen($other) && Storage::disk('meeting')->exists( $other ) ? Storage::disk("meeting")->url( $other ) : false ;
        });
        $record->regulators = $record->regulators()->get()->map(function($regulator){
            $regulator->pdf = strlen( $regulator->pdf ) > 0 
                ? (
                    \Storage::disk('regulator')->exists( $regulator->pdf )
                    ? \Storage::disk('regulator')->url( $regulator->pdf )
                    : (
                        \Storage::disk('document')->exists( $regulator->pdf )
                        ? \Storage::disk('document')->url( $regulator->pdf )
                        : false
                    )
                )
                : false ;
            return [
                "id" => $regulator->id ,
                "fid" => $regulator->fid ,
                "title" => $regulator->title ,
                "objective" => $regulator->objective ,
                "pdf" => $regulator->pdf ,
                "year" => $regulator->year
            ];
        });
        $record->organizations = $record->organizations()->get()->map(function($organization) use( $record ){
            return [
                "id" => $organization->id ,
                "name" => $organization->name
            ];
        });
        $record->members = $record->members()->get()->map(function($member) use( $record ){
            $meetingMember = $record->listMembers()->where('people_id', $member->id)->first();
            return [
                "id" => $member->id ,
                "firstname" => $member->firstname ,
                "lastname" => $member->lastname ,
                "role" => $meetingMember->role ,
                "group" => $meetingMember->group ,
                "remark" => $meetingMember->remark
            ];
        });
        $record->rooms = $record->rooms()->get()->map(function($place) use( $record ){
            return [
                "id" => $place->id ,
                "organization" => $place->organization == null ? null : [
                    'id' => $place->organization->id ,
                    'name' => $place->organization->name
                ] ,
                "name" => $place->name ,
                "desp" => $place->desp
            ];
        });
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
        ],200);
    }
    /**
     * Active function of the account
     */
    public function toggleActive(Request $request){
        $record = RecordModel::find($request->id) ;
        if( $record != null ){
            $record->update([ 'active' => intval( $record->active ) > 0 ? 0 : 1 ]) ;
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
    public function destroy(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ទិន្នន័យនេះមិនមានឡើយ។'
            ],500);
        }
        $tempRecord = $record;
        $tempRecord->with('organizations')->with('regulators')->with('comments')->with('rooms')->with('listMembers');
        if( $record->delete() ){
            /**
             * Delete all the related documents own by this regulator
             * All the documents of the meeting does not allow to delete, need to keep it as references
             */
            // if( $tempRecord->pdf !== null && $tempRecord->pdf !=="" && Storage::disk('meeting')->exists( $tempRecord->pdf ) ){
            //     Storage::disk("meeting")->delete( $tempRecord->pdf  );
            // }
            return response()->json([
                'record' => $tempRecord ,
                'ok' => true ,
                'message' => 'លុបទិន្នបានជោគជ័យ។'
            ],200);
        }
        return response()->json([
            'record' => $tempRecord ,
            'ok' => false ,
            'message' => 'មានបញ្ហាក្នុងការលុបទិន្ន័យ។'
        ],500);
    }
    /**
     * Add member(s)
     */
    // public function addMembers(Request $request){
    //     $record = \App\Models\Meeting\Meeting::find($request->meeting_id);
    //     if( $record != null ){
    //         $result = $record->listMembers()->toggle([$request->people_id]) ;
    //         if( count( $result['attached'] ) > 0 ) {
    //             $result['attached'][0]->update([
    //                 'role' => $request->role ,
    //                 'group' => $request->group ,
    //                 'remark' => $request->remark
    //             ]);
    //         }
    //         return response()->json([
    //             /**
    //              * It will return in the following format : [ attached => [] , detached => [] ]
    //              */
    //             'result' => $result ,
    //             'message' => 'បញ្ចូលសមាជិករួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការបញ្ចូលសមាជិក។'
    //     ],422);
    // }

    /**
     * Remove member(s)
     */
    // public function removeMembers(Request $request){
    //     $record = \App\Models\Meeting\Meeting::find($request->meeting_id);
    //     if( $record != null ){
    //         return response()->json([
    //             'record' => $record->listMembers()->toggle([$request->people_id])['detached'] ,
    //             'message' => 'ដកសមាជិករួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការដកសមាជិក។'
    //     ],422);
    // }

    /**
     * Add room(s)
     */
    // public function addRooms(Request $request){
    //     $record = \App\Models\Meeting\Meeting::find($request->meeting_id);
    //     if( $record != null ){
    //         $result = $record->rooms()->toggle([$request->organization_id])['attached'] ;
    //         if( count( $result['attached'] ) > 0 ) {
    //             $result['attached'][0]->update([
    //                 'room' => $request->room ,
    //                 'remark' => $request->remark
    //             ]);
    //         }
    //         return response()->json([
    //             /**
    //              * It will return in the following format : [ attached => [] , detached => [] ]
    //              */
    //             'result' => $result ,
    //             'message' => 'បញ្ចូលបន្ទប់ប្រជុំរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការបញ្ចូលបន្ទប់ប្រជុំ។'
    //     ],422);
    // }

    /**
     * Remove room(s)
     */
    // public function removeRooms(Request $request){
    //     $record = \App\Models\Meeting\Meeting::find($request->meeting_id);
    //     if( $record != null ){
    //         return response()->json([
    //             'record' => $record->rooms()->toggle([$request->organization_id])['detached'] ,
    //             'message' => 'ដកបន្ទប់ប្រជុំរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការដកបន្ទប់ប្រជុំ។'
    //     ],422);
    // }

    /**
     * Add regulators
     */
    // public function addRegulators(Request $request){
    //     $record = \App\Models\Regulator\Regulator::find($request->meeting_id);
    //     if( $record != null ){
    //         return response()->json([
    //             /**
    //              * It will return in the following format : [ attached => [] , detached => [] ]
    //              */
    //             'result' => $record->regulators()->toggle([$request->regulator_id])['attached'] ,
    //             'message' => 'បញ្ចូលអ្នកអានរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការបញ្ចូលអ្នកអានឯកសារនេះ។'
    //     ],422);
    // }

    /**
     * Remove regulators
     */
    // public function removeRegulators(Request $request){
    //     $record = \App\Models\Regulator\Regulator::find($request->meeting_id);
    //     if( $record != null ){
    //         return response()->json([
    //             'record' => $record->regulators()->toggle([$request->regulator_id])['detached'] ,
    //             'message' => 'ដកអ្នកអានរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការដកអ្នកអានឯកសារនេះ។'
    //     ],422);
    // }

    /**
     * Add organizations
     */
    // public function addOrganizations(Request $request){
    //     $record = \App\Models\Regulator\Regulator::find($request->meeting_id);
    //     if( $record != null ){
    //         return response()->json([
    //             /**
    //              * It will return in the following format : [ attached => [] , detached => [] ]
    //              */
    //             'result' => $record->organizations()->toggle([$request->organization_id])['attached'] ,
    //             'message' => 'បញ្ចូលអ្នកអានរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការបញ្ចូលអ្នកអានឯកសារនេះ។'
    //     ],422);
    // }

    /**
     * Remove organizations
     */
    // public function removeOrganizations(Request $request){
    //     $record = \App\Models\Regulator\Regulator::find($request->document_id);
    //     if( $record != null ){
    //         return response()->json([
    //             'record' => $record->organizations()->toggle([$request->organization_id])['detached'] ,
    //             'message' => 'ដកអ្នកអានរួចរាល់។'
    //         ],200);
    //     }
    //     return response()->json([
    //         'message' => 'មានបញ្ហាក្នុងការដកអ្នកអានឯកសារនេះ។'
    //     ],422);
    // }
    public function statusNew(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_NEW ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function statusMeeting(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_MEETING ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function statusContinue(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_CONTINUE ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function statusChange(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_CHANGE ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function statusDelay(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_DELAY ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function statusFinished(Request $request){
        if( intval( $request->id ) > 0 ){
            $record = RecordModel::find($request->id);
            if( $record == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],500);
            }
            $record->with('organizations')->with('comments')->with('rooms')->with('listMembers');
            return response()->json([
                'record' => $record ,
                'ok' => $record->update(['status'=> \App\Models\Meeting\Meeting::STATUS_FINISHED ]) ,
                'message' => 'ជោគជ័យ'
            ],200);   
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
    }
    public function toggleRegulator(Request $request){
        if( intval( $request->id ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់កិច្ចប្រជុំ។'
            ],500);
        }
        $meeting = RecordModel::find( $request->id );
        if( $meeting == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'កិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        
        if( intval( $request->rid ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់ ឯកសារគតិយុត្ត។'
            ],500);
        }
        $regulator = \App\Models\Regulator\Regulator::find( $request->rid );
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯការគតិយុត្តនេះមិនមានឡើយ។'
            ],500);
        }
        $meeting->regulators()->toggle([ $regulator->id ]);
        $meeting->regulators = $meeting->regulators()->get()->map(function($regulator){
            $regulator = \App\Models\Regulator\Regulator::find( $regulator->id );
            return [
                'id' => $regulator->id ,
                'year' => $regulator->year ,
                'objective' => html_entity_decode( strip_tags( $regulator->objective ) ) ,
                'fid' => $regulator->fid ,
                'pdf' => strlen( $regulator->pdf ) > 0 
                        ? (
                            \Storage::disk('regulator')->exists( $regulator->pdf ) || \Storage::disk('document')->exists( $regulator->pdf ) ? true : false
                        )
                        : false ,
                'type' => $regulator->type
            ];
        });
        return response()->json([
            'record' => $meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    public function toggleOrganization(Request $request){
        if( intval( $request->id ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់កិច្ចប្រជុំ។'
            ],500);
        }
        $meeting = RecordModel::find( $request->id );
        if( $meeting == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'កិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        
        if( intval( $request->oid ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់ ក្រសួងស្ថាប័ន ឬ អង្គភាព។'
            ],500);
        }
        $organization = \App\Models\Organization\Organization::find( $request->oid );
        if( $organization == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ក្រសួងស្ថាប័ននេះមិនមានឡើយ។'
            ],500);
        }
        $meeting->organizations()->toggle([ $organization->id ]);
        $meeting->organizations;
        return response()->json([
            'record' => $meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    public function toggleMember(Request $request){
        if( intval( $request->id ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់កិច្ចប្រជុំ។'
            ],500);
        }
        $meeting = RecordModel::find( $request->id );
        if( $meeting == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'កិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        if( intval( $request->mid ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់សមាជិក។'
            ],500);
        }
        $member = \App\Models\People\People::find( $request->mid );
        if( $member == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សមាជិកនេះមិនមានឡើយ។'
            ],500);
        }

        $memberInformation = [];
        if( strlen( $request->role ) || strlen( $request->group ) || strlen( $request->remark ) ){
            $memberInformation = [ 
                $member->id => [ 
                    'role' => $request->role ,
                    'group' => $request->group ,
                    'remark' => $request->remark
                ] 
            ];
        }else{
            $memberInformation = [ $member->id ];
        }
        /**
         * Remove the members of the meeting
         */
        $result = $meeting->members()->toggle( $memberInformation );
        foreach( $result['detached'] AS $index => $memberId ){
            /**
             * Remove the attendants of the member of the meeting in case they exists
             */
            $meetingMember = $meeting->listMembers()->where('people_id',$memberId)->first();
            if( $meetingMember != null && $meetingMember->attendant != null ){
                $meetingMember->attendant->delete();
            }
        }

        $meeting->list_members = $meeting->listMembers->map(function( $meetingMember ){ 
            $meetingMember->attendant != null ? $meetingMember->attendant->member : null ; 
            $meetingMember->member; 
            return $meetingMember; 
        });
        return response()->json([
            'record' => $meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    // Update Group and Role of Member
    public function updateMemberGroupAndRole(Request $request){
        if( intval( $request->id ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់បញ្ជីសមាជិកកិច្ចប្រជុំ។'
            ],500);
        }
        $meeting = RecordModel::find( $request->meeting_id );
        if( $meeting == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'កិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        $member = \App\Models\People\People::find( $request->people_id );
        if( $member == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សមាជិកនេះមិនមានឡើយ។'
            ],500);
        }

        $memberInformation = [];
        if( strlen( $request->role ) || strlen( $request->group ) || strlen( $request->remark ) ){
            $meeting->members()->updateExistingPivot( $member->id ,[ 
                'role' => $request->role ,
                'group' => $request->group ,
                'remark' => $request->remark
            ]);
        }
        $meeting->list_members = $meeting->listMembers->map(function( $meetingMember ){ 
            $meetingMember->member; 
            return $meetingMember; 
        });
        return response()->json([
            'record' => $meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    public function toggleRoom(Request $request){
        if( intval( $request->id ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់កិច្ចប្រជុំ។'
            ],500);
        }
        $meeting = RecordModel::find( $request->id );
        if( $meeting == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'កិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        
        if( intval( $request->rid ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ចាក់លេខសម្គាល់ បន្ទប់ប្រជុំ។'
            ],500);
        }
        $room = \App\Models\Meeting\Room::find( $request->rid );
        if( $room == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'បន្ទប់ប្រជុំមិនមានឡើយ។'
            ],500);
        }
        $meeting->rooms()->toggle([ $room->id ]);
        $meeting->rooms;
        return response()->json([
            'record' => $meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }
    /**
     * Seichdey Preeng
     */
    public function uploadSeichdeyPreengs(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( intval( $request->id ) > 0 && ( $meeting = \App\Models\Meeting\Meeting::find($request->id) ) !== null ){
                $uploadResult = [
                    'failed' => [] ,
                    'succeed' => []
                ] ;
                $uploadedFiles = [] ;
                foreach( $_FILES['files']['error'] AS $index => $errorCode ){
                    if( $_FILES['files']['error'][$index] === UPLOAD_ERR_OK ){
                        $kbFilesize = round( filesize( $_FILES['files']['tmp_name'][$index] ) / 1024 , 4 );
                        $mbFilesize = round( $kbFilesize / 1024 , 4 );
                        $uploadedFiles[] = [
                            'name' => $_FILES['files']['name'][$index] , 
                            'serial' => Storage::disk('meeting')->putFile( $meeting->id , new File( $_FILES['files']['tmp_name'][$index] ) )
                        ];
                        // unlink the existing file
                        // collect( $meeting->seichdey_preeng )->map(function($preeng){
                        //     strlen($preeng) && Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                        // });

                        $uploadResult['succeed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => 'ជោគជ័យ!'
                        ];
                    }else{
                        $uploadResult['failed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => \App\Helper::getFileErrorMessage( $_FILES['files']['error'][$index] )
                        ];
                    }
                }
                $meeting->seichdey_preeng = array_merge( is_array( $meeting->seichdey_preeng ) ? $meeting->seichdey_preeng : [] , $uploadedFiles );
                $meeting->save();
                $meeting->seichdey_preeng = array_map( function($preeng) use( $meeting ){
                    if( $preeng['serial'] != "" && $preeng['serial'] != null && \Storage::disk('meeting')->exists( $preeng['serial'] ) ) {
                        return [
                            'name' => $preeng['name'] ,
                            'serial' => str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $preeng['serial'] ) 
                        ]; 
                    } 
                    return [
                        'name' => $preeng['name'] ,
                        'serial' => ''
                    ]; ;
                } , $meeting->seichdey_preeng );
                return response([
                    'record' => $meeting ,
                    'upload_result' => $uploadResult ,
                    'message' => 'ជោគជ័យ។'
                ],200);
            }else{
                return response([
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function readPdfPreeng(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = storage_path('data') . '/meetings/' . $meeting->id . '/' . $request->serial . ".pdf" ;
            $ext = pathinfo($path);
            $filename = $request->serial . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function removePdfPreeng(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = null ;
            $ext = null ;
            $filename = '' ;
            /**
             * Check withi the Seichdey Preeng of the meeting 
             */
            
             $meeting->seichdey_preeng = array_filter( $meeting->seichdey_preeng , function( $preeng ) use( $request , $meeting , &$path, &$ext , &$filename ) {
                
                if( str_contains( $preeng['serial'] , $request->serial ) ){
                    $path = storage_path('data') . '/meetings/' . $preeng['serial'] ;
                    $ext = pathinfo($path);
                    $filename = $preeng['name'] ;

                    Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                }

                return !str_contains( $preeng['serial'] , $request->serial );
            });
            
            $meeting->save();

            $meeting->seichdey_preeng = array_map( function($preeng) use( $meeting ){
                $preeng['serial'] = str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $preeng['serial'] );                       
                return $preeng ;
            } , $meeting->seichdey_preeng );

            return response([
                "record" => $meeting ,
                "filename" => $filename,
                "ok" => true 
            ],200);
        }
    }
    /**
     * Minister Seichdey Preeng
     */
    public function uploadMinisterSeichdeyPreengs(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( intval( $request->id ) > 0 && ( $meeting = \App\Models\Meeting\Meeting::find($request->id) ) !== null ){
                $uploadResult = [
                    'failed' => [] ,
                    'succeed' => []
                ] ;
                $uploadedFiles = [] ;
                foreach( $_FILES['files']['error'] AS $index => $errorCode ){
                    if( $_FILES['files']['error'][$index] === UPLOAD_ERR_OK ){
                        $kbFilesize = round( filesize( $_FILES['files']['tmp_name'][$index] ) / 1024 , 4 );
                        $mbFilesize = round( $kbFilesize / 1024 , 4 );
                        $uploadedFiles[] = [
                            'name' => $_FILES['files']['name'][$index] , 
                            'serial' => Storage::disk('meeting')->putFile( $meeting->id , new File( $_FILES['files']['tmp_name'][$index] ) )
                        ];
                        // unlink the existing file
                        // collect( $meeting->minister_preeng )->map(function($preeng){
                        //     strlen($preeng) && Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                        // });

                        $uploadResult['succeed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => 'ជោគជ័យ!'
                        ];
                    }else{
                        $uploadResult['failed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => \App\Helper::getFileErrorMessage( $_FILES['files']['error'][$index] )
                        ];
                    }
                }
                $meeting->minister_preeng = array_merge( is_array( $meeting->minister_preeng ) ? $meeting->minister_preeng : [] , $uploadedFiles );
                $meeting->save();
                $meeting->minister_preeng = array_map( function($preeng) use( $meeting ){
                    if( $preeng['serial'] != "" && $preeng['serial'] != null && \Storage::disk('meeting')->exists( $preeng['serial'] ) ) {
                        return [
                            'name' => $preeng['name'] ,
                            'serial' => str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $preeng['serial'] ) 
                        ]; 
                    } 
                    return [
                        'name' => $preeng['name'] ,
                        'serial' => ''
                    ]; ;
                } , $meeting->minister_preeng );
                return response([
                    'record' => $meeting ,
                    'upload_result' => $uploadResult ,
                    'message' => 'ជោគជ័យ។'
                ],200);
            }else{
                return response([
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function readMinisterPdfPreeng(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = storage_path('data') . '/meetings/' . $meeting->id . '/' . $request->serial . ".pdf" ;
            $ext = pathinfo($path);
            $filename = $request->serial . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function removeMinisterPdfPreeng(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = null ;
            $ext = null ;
            $filename = '' ;
            /**
             * Check withi the Seichdey Preeng of the meeting 
             */
            
             $meeting->minister_preeng = array_filter( $meeting->minister_preeng , function( $preeng ) use( $request , $meeting , &$path, &$ext , &$filename ) {
                
                if( str_contains( $preeng['serial'] , $request->serial ) ){
                    $path = storage_path('data') . '/meetings/' . $preeng['serial'] ;
                    $ext = pathinfo($path);
                    $filename = $preeng['name'] ;

                    Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                }

                return !str_contains( $preeng['serial'] , $request->serial );
            });
            
            $meeting->save();

            $meeting->minister_preeng = array_map( function($preeng) use( $meeting ){
                $preeng['serial'] = str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $preeng['serial'] );                       
                return $preeng ;
            } , $meeting->minister_preeng );

            return response([
                "record" => $meeting ,
                "filename" => $filename,
                "ok" => true 
            ],200);
        }
    }
    /**
     * Report
     */
    public function uploadReports(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( intval( $request->id ) > 0 && ( $meeting = \App\Models\Meeting\Meeting::find($request->id) ) !== null ){
                $uploadResult = [
                    'failed' => [] ,
                    'succeed' => []
                ] ;
                $uploadedFiles = [] ;
                foreach( $_FILES['files']['error'] AS $index => $errorCode ){
                    if( $_FILES['files']['error'][$index] === UPLOAD_ERR_OK ){
                        $kbFilesize = round( filesize( $_FILES['files']['tmp_name'][$index] ) / 1024 , 4 );
                        $mbFilesize = round( $kbFilesize / 1024 , 4 );
                        $uploadedFiles[] = [
                            'name' => $_FILES['files']['name'][$index] , 
                            'serial' => Storage::disk('meeting')->putFile( $meeting->id , new File( $_FILES['files']['tmp_name'][$index] ) )
                        ];
                        // unlink the existing file
                        // collect( $meeting->seichdey_preeng )->map(function($preeng){
                        //     strlen($preeng) && Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                        // });

                        $uploadResult['succeed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => 'ជោគជ័យ!'
                        ];
                    }else{
                        $uploadResult['failed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => \App\Helper::getFileErrorMessage( $_FILES['files']['error'][$index] )
                        ];
                    }
                }
                $meeting->reports = array_merge( is_array( $meeting->reports ) ? $meeting->reports : [] , $uploadedFiles );
                $meeting->save();
                $meeting->reports = array_map( function($report) use( $meeting ){
                    if( $report['serial'] != "" && $report['serial'] != null && \Storage::disk('meeting')->exists( $report['serial'] ) ) {
                        return [
                            'name' => $report['name'] ,
                            'serial' => str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $report['serial'] ) 
                        ]; 
                    } 
                    return [
                        'name' => $report['name'] ,
                        'serial' => ''
                    ]; ;
                } , $meeting->reports );
                return response([
                    'record' => $meeting ,
                    'upload_result' => $uploadResult ,
                    'message' => 'ជោគជ័យ។'
                ],200);
            }else{
                return response([
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function readPdfReport(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = storage_path('data') . '/meetings/' . $meeting->id . '/' . $request->serial . ".pdf" ;
            $ext = pathinfo($path);
            $filename = $request->serial . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function removePdfReport(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = null ;
            $ext = null ;
            $filename = '' ;
            /**
             * Check withi the Seichdey Preeng of the meeting 
             */
            
             $meeting->reports = array_filter( $meeting->reports , function( $report ) use( $request , $meeting , &$path, &$ext , &$filename ) {
                
                if( str_contains( $report['serial'] , $request->serial ) ){
                    $path = storage_path('data') . '/meetings/' . $report['serial'] ;
                    $ext = pathinfo($path);
                    $filename = $report['name'] ;

                    Storage::disk('meeting')->exists( $report['serial'] ) ? Storage::disk('meeting')->delete( $report['serial'] ) : false ;
                }

                return !str_contains( $report['serial'] , $request->serial );
            });
            
            $meeting->save();

            $meeting->reports = array_map( function($report) use( $meeting ){
                $report['serial'] = str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $report['serial'] );                       
                return $report ;
            } , $meeting->reports );

            return response([
                "record" => $meeting ,
                "filename" => $filename,
                "ok" => true 
            ],200);
        }
    }
    /**
     * Tech Report
     */
    public function uploadTechReports(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( intval( $request->id ) > 0 && ( $meeting = \App\Models\Meeting\Meeting::find($request->id) ) !== null ){
                $uploadResult = [
                    'failed' => [] ,
                    'succeed' => []
                ] ;
                $uploadedFiles = [] ;
                foreach( $_FILES['files']['error'] AS $index => $errorCode ){
                    if( $_FILES['files']['error'][$index] === UPLOAD_ERR_OK ){
                        $kbFilesize = round( filesize( $_FILES['files']['tmp_name'][$index] ) / 1024 , 4 );
                        $mbFilesize = round( $kbFilesize / 1024 , 4 );
                        $uploadedFiles[] = [
                            'name' => $_FILES['files']['name'][$index] , 
                            'serial' => Storage::disk('meeting')->putFile( $meeting->id , new File( $_FILES['files']['tmp_name'][$index] ) )
                        ];
                        // unlink the existing file
                        // collect( $meeting->seichdey_preeng )->map(function($preeng){
                        //     strlen($preeng) && Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                        // });

                        $uploadResult['succeed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => 'ជោគជ័យ!'
                        ];
                    }else{
                        $uploadResult['failed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => \App\Helper::getFileErrorMessage( $_FILES['files']['error'][$index] )
                        ];
                    }
                }
                $meeting->tech_reports = array_merge( is_array( $meeting->tech_reports ) ? $meeting->tech_reports : [] , $uploadedFiles );
                $meeting->save();
                $meeting->tech_reports = array_map( function($report) use( $meeting ){
                    if( $report['serial'] != "" && $report['serial'] != null && \Storage::disk('meeting')->exists( $report['serial'] ) ) {
                        return [
                            'name' => $report['name'] ,
                            'serial' => str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $report['serial'] ) 
                        ]; 
                    } 
                    return [
                        'name' => $report['name'] ,
                        'serial' => ''
                    ]; ;
                } , $meeting->tech_reports );
                return response([
                    'record' => $meeting ,
                    'upload_result' => $uploadResult ,
                    'message' => 'ជោគជ័យ។'
                ],200);
            }else{
                return response([
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function readPdfTechReport(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = storage_path('data') . '/meetings/' . $meeting->id . '/' . $request->serial . ".pdf" ;
            $ext = pathinfo($path);
            $filename = $request->serial . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function removePdfTechReport(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = null ;
            $ext = null ;
            $filename = '' ;
            /**
             * Check withi the Seichdey Preeng of the meeting 
             */
            
             $meeting->tech_reports = array_filter( $meeting->tech_reports , function( $report ) use( $request , $meeting , &$path, &$ext , &$filename ) {
                
                if( str_contains( $report['serial'] , $request->serial ) ){
                    $path = storage_path('data') . '/meetings/' . $report['serial'] ;
                    $ext = pathinfo($path);
                    $filename = $report['name'] ;

                    Storage::disk('meeting')->exists( $report['serial'] ) ? Storage::disk('meeting')->delete( $report['serial'] ) : false ;
                }

                return !str_contains( $report['serial'] , $request->serial );
            });
            
            $meeting->save();

            $meeting->tech_reports = array_map( function($report) use( $meeting ){
                $report['serial'] = str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $report['serial'] );                       
                return $report ;
            } , $meeting->tech_reports );

            return response([
                "record" => $meeting ,
                "filename" => $filename,
                "ok" => true 
            ],200);
        }
    }
    /**
     * Other Document
     */
    public function uploadOtherDocuments(Request $request){
        $user = \Auth::user();
        if( $user ){
            if( intval( $request->id ) > 0 && ( $meeting = \App\Models\Meeting\Meeting::find($request->id) ) !== null ){
                $uploadResult = [
                    'failed' => [] ,
                    'succeed' => []
                ] ;
                $uploadedFiles = [] ;
                foreach( $_FILES['files']['error'] AS $index => $errorCode ){
                    if( $_FILES['files']['error'][$index] === UPLOAD_ERR_OK ){
                        $kbFilesize = round( filesize( $_FILES['files']['tmp_name'][$index] ) / 1024 , 4 );
                        $mbFilesize = round( $kbFilesize / 1024 , 4 );
                        $uploadedFiles[] = [
                            'name' => $_FILES['files']['name'][$index] , 
                            'serial' => Storage::disk('meeting')->putFile( $meeting->id , new File( $_FILES['files']['tmp_name'][$index] ) )
                        ];
                        // unlink the existing file
                        // collect( $meeting->seichdey_preeng )->map(function($preeng){
                        //     strlen($preeng) && Storage::disk('meeting')->exists( $preeng['serial'] ) ? Storage::disk('meeting')->delete( $preeng['serial'] ) : false ;
                        // });

                        $uploadResult['succeed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => 'ជោគជ័យ!'
                        ];
                    }else{
                        $uploadResult['failed'][] = [
                            'name' => $_FILES['files']['name'][$index] ,
                            'message' => \App\Helper::getFileErrorMessage( $_FILES['files']['error'][$index] )
                        ];
                    }
                }
                $meeting->other_documents = array_merge( is_array( $meeting->other_documents ) ? $meeting->other_documents : [] , $uploadedFiles );
                $meeting->save();
                $meeting->other_documents = array_map( function($otherDocument) use( $meeting ){
                    if( $otherDocument['serial'] != "" && $otherDocument['serial'] != null && \Storage::disk('meeting')->exists( $otherDocument['serial'] ) ) {
                        return [
                            'name' => $otherDocument['name'] ,
                            'serial' => str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $otherDocument['serial'] ) 
                        ]; 
                    } 
                    return [
                        'name' => $otherDocument['name'] ,
                        'serial' => ''
                    ]; ;
                } , $meeting->other_documents );
                return response([
                    'record' => $meeting ,
                    'upload_result' => $uploadResult ,
                    'message' => 'ជោគជ័យ។'
                ],200);
            }else{
                return response([
                    'message' => 'មិនមានកិច្ចប្រជុំនេះឡើយ។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    public function readPdfOtherDocument(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = storage_path('data') . '/meetings/' . $meeting->id . '/' . $request->serial . ".pdf" ;
            $ext = pathinfo($path);
            $filename = $request->serial . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function removePdfOtherDocument(Request $request)
    {
        $meeting = RecordModel::findOrFail($request->id);
        if($meeting) {
            $path = null ;
            $ext = null ;
            $filename = '' ;
            /**
             * Check withi the Seichdey Preeng of the meeting 
             */
            
             $meeting->other_documents = array_filter( $meeting->other_documents , function( $otherDocument ) use( $request , $meeting , &$path, &$ext , &$filename ) {
                
                if( str_contains( $otherDocument['serial'] , $request->serial ) ){
                    $path = storage_path('data') . '/meetings/' . $otherDocument['serial'] ;
                    $ext = pathinfo($path);
                    $filename = $otherDocument['name'] ;

                    Storage::disk('meeting')->exists( $otherDocument['serial'] ) ? Storage::disk('meeting')->delete( $otherDocument['serial'] ) : false ;
                }

                return !str_contains( $otherDocument['serial'] , $request->serial );
            });
            
            $meeting->save();

            $meeting->other_documents = array_map( function($otherDocument) use( $meeting ){
                $otherDocument['serial'] = str_replace( [ $meeting->id . '/' , '.pdf' ] , '' , $otherDocument['serial'] );                       
                return $otherDocument ;
            } , $meeting->other_documents );

            return response([
                "record" => $meeting ,
                "filename" => $filename,
                "ok" => true 
            ],200);
        }
    }

    /**
     * TV - Meeting
     */
    public function tvMeetings(Request $request){

        /** Format from query string */
        $search = isset( $request->search ) && strlen( trim($request->search ) > 0 ) ? trim($request->search) : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    // [
                    //     'field' => 'date' ,
                    //     'value' => \Carbon\Carbon::now()->format('Y-m-d')
                    // ]
                    [
                        'field' => 'active' ,
                        'value' => 1
                    ]
                ],
                'in' => [
                    /*
                      const STATUSES = [
                        1 => 'NEW' ,
                        2 => 'MEETING' ,
                        4 => 'CONTINUE' ,
                        8 => 'CHANGED' ,
                        16 => 'DELAIED' ,
                        32 => 'FINISHED' ,
                    ];
                     */
                    [
                        'field' => 'status' ,
                        'value' => [ 1, 2 ]
                    ]
                ] ,
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
                    'objective', 'date' , 'contact_info' , 'summary'
                ]
            ],
            "order" => [
                // 'field' => 'timestamp_start' ,
                // 'by' => 'asc'
                [
                    'field' => 'date' ,
                    'by' => 'asc'
                ] ,
                [
                    'field' => 'start' ,
                    'by' => 'asc'
                ]
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields ,
        // [
        //     /**
        //      * custom the value of the field
        //      */
        //     'seichdey_preeng' => function($record){
        //         $preeng = [] ;
        //         if( is_array( $record->seichdey_preeng ) && count( $record->seichdey_preeng ) ){
        //             foreach( $record->seichdey_preeng AS $index => $pdf ){
        //                 if( $pdf['serial'] != "" && $pdf['serial'] != null && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $preeng[] = [
        //                     'name' => $pdf['name'] ,
        //                     'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
        //                 ];
        //             }
        //         }
        //         return $preeng ;
        //     },
        //     'reports' => function($record){
        //         $reports = [] ;
        //         if( is_array( $record->reports ) && count( $record->reports ) ){
        //             foreach( $record->reports AS $index => $pdf ){
        //                 if( $pdf['serial'] != "" && $pdf['serial'] != null && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $reports[] = [
        //                     'name' => $pdf['name'] ,
        //                     'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
        //                 ];
        //             }
        //         }
        //         return $reports;
        //     },
        //     'other_documents' => function($record){
        //         $documents = [] ;
        //         if( is_array( $record->other_documents ) && count( $record->other_documents ) ){
        //             foreach( $record->other_documents AS $index => $pdf ){
        //                 if( $pdf['serial'] != "" && $pdf['serial'] != null && \Storage::disk('meeting')->exists( $pdf['serial'] ) ) $documents[] = [
        //                     'name' => $pdf['name'] ,
        //                     'serial' => str_replace( [ $record->id . '/' , '.pdf' ] , '' , $pdf['serial'] )                            
        //                 ];
        //             }
        //         }
        //         return $documents ;
        //     },
        //    'objective' => function($record){
        //         return html_entity_decode( strip_tags( $record->objective ) );
        //     }
        // ]
            false ,
            false ,
            [
                'status_label' => function($record){
                    return $record->getStatusAsString();
                }
            ]
        );

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'comments' => [ 'id' , 'people_id' , 'comment' ] ,
            'organizations' => [ 'id' , 'name' , 'desp' ] ,
            'type' => [ 'id' , 'name' , 'desp' ] ,
            'rooms' => [ 'id' , 'name' , 'desp' ] ,
            // 'listMembers' => [ 'id' , 'people_id' , 'role' , 'group' , 'remark'  
            //     // , 'member' => [
            //     //     'id' , 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 
            //     //     'officers' => [
            //     //         'id' , 'email' , 'phone' , 'countesy_id' , 'position_id' , 'organization_id' ,
            //     //         'countesy' => [ 'id' , 'name' , 'desp' ] ,
            //     //         'position' => [ 'id' , 'name' , 'desp' ] ,
            //     //         'organization' => [ 'id' , 'name' , 'desp' ]
            //     //     ]
            //     // ]
            // ] ,
            // 'members' => [ 'id' , 'firstname' , 'lastname' , 'phone' ,'email' ] ,
            // 'regulators' => [ 'id', 'fid' , 'title' , 'objective', 'year' , 'pdf' ] 
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);

        $responseData['records'] = $responseData['records']->map(function($meeting){
            $meetingObj = RecordModel::find( $meeting['id'] );
            $meetingObj->updateStatus();
            $meeting['status'] = $meetingObj->status ;
            // $meeting['full_date'] = \Carbon\Carbon::parse( $meeting['date'] )->isoFormat('ddd D MMM YYYY');
            $meeting['full_date'] = \App\Helper::toKdate( $meeting['date'] , true );
            $meeting['listMembers'] = $meetingObj->listMembers()->where([
                'group' => 'lead_meeting' ,
                'role' => 'leader'
            ]) 
            ->get()->map(function($meetingMember){
                $people = \App\Models\People\People::select([ 'id' , 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' ])->find( $meetingMember->people_id );
                $people->countesies;
                $people->positions;
                $people->officers = $people->officers()->select(['id' , 'email' , 'phone' , 'countesy_id' , 'position_id' , 'organization_id'])->get()->map(function($officer)  {
                    // $lastname = $officer->countesy != null ? $officer->countesy->name : '' ;
                    // $lastname .= $officer->position != null ? $officer->position->name : '' ;
                    $officer->countesy;
                    $officer->position;
                    $officer->organization;
                    return $officer;
                });
                // $people->lastname = $lastname . ' ' . $people->lastname ;
                $people->officer;
                $people->organizations;
                $meetingMember->member = $people ;
                return $meetingMember ;
            });
            return $meeting ;
        });

        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    
    public function start(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        if( intval( $request->id ) > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            $record->actual_start = \Carbon\Carbon::now()->format('H:i');
            $record->status = RecordModel::STATUS_MEETING ;
            return response()->json([
                    'ok' => $record->save() ,
                    'message' => 'រួចរាល់'
                ]
            ,200);
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ប្រជុំ។'
            ], 422);
        }
    }
    public function end(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        if( intval( $request->id ) > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            $record->actual_end = \Carbon\Carbon::now()->format('H:i');
            $record->status = RecordModel::STATUS_FINISHED ;
            return response()->json([
                    'ok' => $record->save() ,
                    'message' => 'រួចរាល់'
                ]
            ,200);
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ប្រជុំ។'
            ], 422);
        }
    }
    public function checkAttendantToggle(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        
        $meetingMember = \App\Models\Meeting\MeetingMember::find( $request->meeting_member_id );
        if( $meetingMember == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សមាជិកនេះមិនមានក្នុងបញ្ជីសមាជិកប្រជុំឡើយ។'
            ],500);
        }
        $member = \App\Models\People\People::find( $request->member_id );
        if( $member == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'រកមិនឃើញសមាជិកនេះឡើយ។'
            ],500);
        }
        if( $meetingMember->attendant == null ) {
            // The attendant has not exist yet, then create new one
            $meetingMember->attendant()->create([
                'meeting_member_id' => $meetingMember->id ,
                'people_id'=>$member->id
            ]);
        }else if( $meetingMember->attendant != null ){
            // The attendant has already exist then update the member
            if( $meetingMember->attendant->people_id == $member->id ){
                $meetingMember->attendant->updated_by = $user->id ;
                $meetingMember->attendant->delete();
            }else{
                $meetingMember->attendant->update([
                    'people_id'=>$member->id ,
                    'checktime'=> \Carbon\Carbon::now()->format("Y-m-d H:i") ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id
                ]);
            }
        }
        $meetingMember->meeting->list_members = $meetingMember->meeting->listMembers->map(function( $meetingMember ){ 
            $meetingMember->attendant != null ? $meetingMember->attendant->member : null ;
            $meetingMember->member; 
            return $meetingMember; 
        });
        return response()->json([
            'record' => $meetingMember->meeting ,
            'ok' => true ,
            'message' => 'ជោគជ័យ។'
        ],200);
    }

    public function history(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់។'
            ],500);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ទិន្នន័យដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],500);
        }
        if( $record->legalDraft == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សេចក្ដីព្រាងនៃកិច្ចប្រជុំនេះមិនមានឡើយ។'
            ],500);
        }
        
        $record->legalDraft->meetings = $record->legalDraft->meetings()->orderby('id','asc')->get()->map(function($record){
            $record->updateStatus();
            $record->createdBy ;
            $record->updatedBy ;
            $record->type ;

            $record->seichdey_preeng = collect( $record->seichdey_preeng )->map(function($preeng){
                return strlen($preeng) && Storage::disk('meeting')->exists( $preeng ) ? Storage::disk("meeting")->url( $preeng ) : false ;
            });
            $record->reports = collect( $record->reports )->map(function($report){
                return strlen($report) && Storage::disk('meeting')->exists( $report ) ? Storage::disk("meeting")->url( $report ) : false ;
            });
            $record->other_documents = collect( $record->other_documents )->map(function($other){
                return strlen($other) && Storage::disk('meeting')->exists( $other ) ? Storage::disk("meeting")->url( $other ) : false ;
            });
            $record->regulators = $record->regulators()->get()->map(function($regulator){
                $regulator->pdf = strlen( $regulator->pdf ) > 0 
                    ? (
                        \Storage::disk('regulator')->exists( $regulator->pdf )
                        ? \Storage::disk('regulator')->url( $regulator->pdf )
                        : (
                            \Storage::disk('document')->exists( $regulator->pdf )
                            ? \Storage::disk('document')->url( $regulator->pdf )
                            : false
                        )
                    )
                    : false ;
                return [
                    "id" => $regulator->id ,
                    "fid" => $regulator->fid ,
                    "title" => $regulator->title ,
                    "objective" => $regulator->objective ,
                    "pdf" => $regulator->pdf ,
                    "year" => $regulator->year
                ];
            });
            $record->organizations = $record->organizations()->get()->map(function($organization) use( $record ){
                return [
                    "id" => $organization->id ,
                    "name" => $organization->name
                ];
            });
            $record->members = $record->members()->get()->map(function($member) use( $record ){
                $meetingMember = $record->listMembers()->where('people_id', $member->id)->first();
                return [
                    "id" => $member->id ,
                    "firstname" => $member->firstname ,
                    "lastname" => $member->lastname ,
                    "role" => $meetingMember->role ,
                    "group" => $meetingMember->group ,
                    "remark" => $meetingMember->remark
                ];
            });
            $record->rooms = $record->rooms()->get()->map(function($place) use( $record ){
                return [
                    "id" => $place->id ,
                    "organization" => $place->organization == null ? null : [
                        'id' => $place->organization->id ,
                        'name' => $place->organization->name
                    ] ,
                    "name" => $place->name ,
                    "desp" => $place->desp
                ];
            });
            // List members
            $record->listMembers = $record->listMembers->map(function($meetingMember){
                return [
                    'id' => $meetingMember->id ,
                    'role' => $meetingMember->role ,
                    'group' => $meetingMember->group ,
                    'remark' => $meetingMember->remark ,
                    'member' => $meetingMember->member == null ? null : [ 
                        'id' => $meetingMember->member->id , 
                        'firstname' => $meetingMember->member->firstname , 
                        'lastname' => $meetingMember->member->lastname
                    ] ,
                    'attendant' => $meetingMember->attendant == null ? null :
                        [ 
                            'id' => $meetingMember->attendant->id , 
                            'checktime' => $meetingMember->attendant->checktime , 
                            'remark' => $meetingMember->attendant->remark , 
                            'member' => $meetingMember->attendant->member == null ? null : 
                            [ 
                                'id' => $meetingMember->attendant->member->id , 
                                'firstname' => $meetingMember->attendant->member->firstname , 
                                'lastname' => $meetingMember->attendant->member->lastname
                            ] 
                        ]
                ];
            });
            return $record ;
        });
        return response()->json([
            'record' => $record->legalDraft ,
            'total' => [
                'types' => RecordModel::getMeetingsByType() ,
                'statuses' => RecordModel::getMeetingsByStatus() ,
                'days' => $record->legalDraft->totalSpentDays() ,
                'time' => $record->legalDraft->totalSpentMinutes()
            ] ,
            'ok' => true ,
            'message' => 'រួចរាល់។'
        ],200);
    }
    public function uploadPdf(Request $request){
        $user = \Auth::user();
        if( $user ){
            $phpFileUploadErrors = [
                0 => 'មិនមានបញ្ហាជាមួយឯកសារឡើយ។',
                1 => "ទំហំឯកសារធំហួសកំណត់ " . ini_get("upload_max_filesize"),
                2 => 'ទំហំឯកសារធំហួសកំណត់នៃទំរង់បញ្ចូលទិន្នន័យ ' . ini_get('post_max_size'),
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            ];
            if( isset( $_FILES['files'] ) && $_FILES['files']['error'] > 0 ){
                return response()->json([
                    'ok' => false ,
                    'message' => $phpFileUploadErrors[ $_FILES['files']['error'] ]
                ],403);
            }
            $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $record = RecordModel::find($request->id) ) !== null ){
                /**
                 * Create folder for storing
                 */
                $folderHelper = true ;
                $className = strtolower( get_class( $this ) ) ;
                if( strpos( $className , '/' ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '/' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }else if( strpos( $className , "\\" ) !== false ){
                    $moduleFolder = implode( '/' , array_filter( explode( '\\' , $className ) , function( $c ){
                        return $c != 'app' && $c != 'http' && $c != 'controllers' && $c != 'api' ;
                    } ) ) ;
                }
                
                $userFolder = $moduleFolder.'/'.$user->id ;
                if( !Storage::disk('data')->exists( $userFolder )){
                    if( !Storage::disk('data')->makeDirectory( $userFolder ) ){
                        return response()->json([
                            'ok' => false ,
                            'message' => "មិនអាចបង្កើតថតឯកសារបាន។"
                        ],500);
                    }
                }
                
                $uniqeName = Storage::disk('data')->putFile( $userFolder , new File( $_FILES['files']['tmp_name'] ) );
                $record->pdf = $uniqeName ;
                $record->save();
                if( Storage::disk('data')->exists( $record->pdf ) ){
                    $record->pdf = Storage::disk('data')->url( $record->pdf  );
                    // $record->pdf = true ;
                    return response([
                        'record' => $record ,
                        'message' => 'ជោគជ័យ។'
                    ],200);
                }else{
                    return response([
                        'record' => $record ,
                        'message' => 'មិនមានតួនាទីដែលស្វែងរកឡើយ។'
                    ],403);
                }
            }else{
                return response([
                    'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់តួនាទី។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
}
