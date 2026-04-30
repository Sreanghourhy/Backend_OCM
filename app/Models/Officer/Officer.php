<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Officer extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
        if( $obj->image !== "" && $obj->image !== null ) \Storage::disk('public')->delete($obj->image);
        });
    }
    public function rank(){
        return $this->belongsTo( \App\Models\Officer\Rank::class , 'rank_id' , 'id' );
    }
    public function position(){  //in progess
        return $this->belongsTo( \App\Models\Organization\OrganizationStructurePosition::class , 'position_id' , 'id' );
    }
    public function organization(){ //in progess
        return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
    }
    public function countesy(){
        return $this->belongsTo( \App\Models\People\Countesy::class , 'countesy_id' , 'id' );
    }
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
    // Scope to ensure people record exists
    public function scopeWithPeople($query)
    {
        return $query->whereHas('people');
    }
    public function card(){
        return $this->hasOne( \App\Models\People\Card::class , 'officer_id', 'id' );
    }
    public function user(){
        return $this->belongsTo( \App\Models\User::class , 'user_id' ,'id' );
    }
    public function jobs(){
        return $this->hasMany( \App\Models\Officer\OfficerJob::class , 'officer_id' , 'id' );
    }
    public function getCurrentJob(){
        return $this->jobs()->whereNull('end')->orderby('start','desc')->first();
    }
    public function jobBackgrounds(){
        return $this->hasMany( \App\Models\Officer\OfficerJobBackground::class , 'officer_id' , 'id' );
    }
    public function rankingByCertificates(){
        return $this->hasMany( \App\Models\Officer\OfficerRankByCertificate::class , 'officer_id' , 'id' );
    }
    public function rankingByWorkings(){
        return $this->hasMany( \App\Models\Officer\OfficerRankByWorking::class , 'officer_id' , 'id' );
    }
    public function pendingWorks(){
        return $this->hasMany( \App\Models\Officer\OfficerWorkPending::class , 'officer_id' , 'id' );
    }
    public function paneltyHistories(){
        return $this->hasMany( \App\Models\Officer\OfficerPenaltyHistory::class , 'officer_id' , 'id' );
    }
    public function medalHistories(){
        return $this->hasMany( \App\Models\Officer\OfficerMedalHistory::class , 'officer_id' , 'id' );
    }

    /**
     * Meetings
     */
    public function meetings(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' );
    }

    public function meetingsJoinedAsLeaderOfLeadMeeting(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','leader')->where('group','lead_meeting');
    }

    public function meetingsJoinedAsDeputyLeaderOfLeadMeeting(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','deputy_leader')->where('group','lead_meeting');
    }

    public function meetingsJoinedAsMemberOfLeadMeeting(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','member')->where('group','lead_meeting');
    }

    public function meetingsJoinedAsLeaderOfDefender(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','leader')->where('group','defender');
    }

    public function meetingsJoinedAsDeputyLeaderOfDefender(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','deputy_leader')->where('group','defender');
    }

    public function meetingsJoinedAsMemberOfDefender(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','member')->where('group','defender');
    }

    public function meetingsJoinedAsMember(){
    return $this->belongsToMany( Meeting::class , MeetingMember::class , 'people_id' , 'meeting_id' )
        ->wherePivot('role','member')->where('group','audient');
    }

    public function ministries(){
    return $this->belongsToMany('\App\Models\Ministry','ministry_people','people_id','ministry_id');
    }

    /**
     * Get total meetings which lead by each leader and each meeting types
     */
    public static function totalMeetingsByType($leaderId =false , $creatorIds=[]){
    $builder = static::whereIn('id',
        // Fetching the people_id from meeting_members table to cut down the number of the records
        \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
    );
    if( $leaderId != false && intval( $leaderId ) > 0 ){
        $builder->where('id',$leaderId);
    }
    return $builder->get()->map(function($people) use( $creatorIds ) {
        $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting();
        if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
        // Copy the builder
        // $copyBuilder = new \Illuminate\Database\Eloquent\Builder(clone $builder->getQuery());
        // $copyBuilder->setModel($builder->getModel());
        return [
        'total' => $builder->count() ,
        'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
        'people' => [
            'id' => $people->id ,
            'lastname' => $people->lastname ,
            'firstname' => $people->firstname ,
            'countesies' => $people->countesies ,
            'organizations' => $people->organizations ,
            'positions' => $people->positions
        ] ,
        'meetings' => $builder->get()->map(function($record){
            $record->updateStatus();
            $record->createdBy ;
            $record->updatedBy ;
            $record->type ;

            // $record->seichdey_preeng = collect( $record->seichdey_preeng )->map(function($preeng){
            //     return strlen($preeng) && Storage::disk('meeting')->exists( $preeng ) ? Storage::disk("meeting")->url( $preeng ) : false ;
            // });
            // $record->reports = collect( $record->reports )->map(function($report){
            //     return strlen($report) && Storage::disk('meeting')->exists( $report ) ? Storage::disk("meeting")->url( $report ) : false ;
            // });
            // $record->other_documents = collect( $record->other_documents )->map(function($other){
            //     return strlen($other) && Storage::disk('meeting')->exists( $other ) ? Storage::disk("meeting")->url( $other ) : false ;
            // });
            // $record->regulators = $record->regulators()->get()->map(function($regulator){
            //     $regulator->pdf = strlen( $regulator->pdf ) > 0
            //         ? (
            //             \Storage::disk('regulator')->exists( $regulator->pdf )
            //             ? \Storage::disk('regulator')->url( $regulator->pdf )
            //             : (
            //                 \Storage::disk('document')->exists( $regulator->pdf )
            //                 ? \Storage::disk('document')->url( $regulator->pdf )
            //                 : false
            //             )
            //         )
            //         : false ;
            //     return [
            //         "id" => $regulator->id ,
            //         "fid" => $regulator->fid ,
            //         "title" => $regulator->title ,
            //         "objective" => $regulator->objective ,
            //         "pdf" => $regulator->pdf ,
            //         "year" => $regulator->year
            //     ];
            // });
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
        }) ,
        'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
            return [
                'type' => [
                    'id' => $meeting->type->id ,
                    'name' => $meeting->type->name ,
                ],
                'total' => $meeting->total
            ];
        })
        ];
    });
    }
    /**
     * Get total meetings which lead by each leader and each meeting types within this week
     */
    public static function totalMeetingsByTypeThisWeek($creatorIds=[]){
    $builder = static::whereIn('id',
        // Fetching the people_id from meeting_members table to cut down the number of the records
        \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
    );
    return $builder->get()->map(function($people) use( $creatorIds ) {
        $today = Carbon::now();
        $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting()
        ->whereBetween('official_date', [ $today->startOfWeek()->format('Y-m-d') , $today->endOfWeek()->format('Y-m-d') ] );
        if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
        return [
        'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
        'people' => [
            'id' => $people->id ,
            'lastname' => $people->lastname ,
            'firstname' => $people->firstname ,
            'countesies' => $people->countesies ,
            'organizations' => $people->organizations ,
            'positions' => $people->positions
        ] ,
        'total' => $builder->count() ,
        'meetings' => $builder->get()->map(function($record){
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
        }) ,
        'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
            return [
                'type' => [
                    'id' => $meeting->type->id ,
                    'name' => $meeting->type->name ,
                ],
                'total' => $meeting->total
            ];
        })
        ];
    });
    }
    /**
     * Get total meetings which lead by each leader and each meeting types within this month
     */
    public static function totalMeetingsByTypeThisMonth($creatorIds=[]){
    $builder = static::whereIn('id',
        // Fetching the people_id from meeting_members table to cut down the number of the records
        \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
    );
    return $builder->get()->map(function($people) use( $creatorIds ) {
        $today = Carbon::now();
        $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting()
        ->whereBetween('official_date', [ $today->startOfMonth()->format('Y-m-d') , $today->endOfMonth()->format('Y-m-d') ] );
        if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
        return [
        'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
        'people' => [
            'id' => $people->id ,
            'lastname' => $people->lastname ,
            'firstname' => $people->firstname ,
            'countesies' => $people->countesies ,
            'organizations' => $people->organizations ,
            'positions' => $people->positions
        ] ,
        'total' => $builder->count() ,
        'meetings' => $builder->get()->map(function($record){
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
        }) ,
        'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
            return [
                'type' => [
                    'id' => $meeting->type->id ,
                    'name' => $meeting->type->name ,
                ],
                'total' => $meeting->total
            ];
        })
        ];
    });
    }
    /**
     * Get total meetings which lead by each leader and each meeting types within this first term
     */
    public static function totalMeetingsByTypeFirstTerm($creatorIds=[]){
    $builder = static::whereIn('id',
        // Fetching the people_id from meeting_members table to cut down the number of the records
        \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
    );
    return $builder->get()->map(function($people) use( $creatorIds ) {
        $start = Carbon::now()->startOfYear();
        $end = $start->copy()->addMonths(2);
        $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting()
        ->whereBetween('official_date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
        if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
        return [
        'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
        'people' => [
            'id' => $people->id ,
            'lastname' => $people->lastname ,
            'firstname' => $people->firstname ,
            'countesies' => $people->countesies ,
            'organizations' => $people->organizations ,
            'positions' => $people->positions
        ] ,
        'total' => $builder->count() ,
        'meetings' => $builder->get()->map(function($record){
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
        }) ,
        'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
            return [
                'type' => [
                    'id' => $meeting->type->id ,
                    'name' => $meeting->type->name ,
                ],
                'total' => $meeting->total
            ];
        })
        ];
    });
    }
    /**
     * Get total meetings which lead by each leader and each meeting types within this first semester
     */
    public static function totalMeetingsByTypeFirstSemester($creatorIds=[]){
    $builder = static::whereIn('id',
        // Fetching the people_id from meeting_members table to cut down the number of the records
        \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
    );
    return $builder->get()->map(function($people) use( $creatorIds ) {
        $start = Carbon::now()->startOfYear();
        $end = $start->copy()->addMonths(5);
        $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting()
        ->whereBetween('official_date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
        if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
        return [
        'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
        'people' => [
            'id' => $people->id ,
            'lastname' => $people->lastname ,
            'firstname' => $people->firstname ,
            'countesies' => $people->countesies ,
            'organizations' => $people->organizations ,
            'positions' => $people->positions
        ] ,
        'total' => $builder->count() ,
        'meetings' => $builder->get()->map(function($record){
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
        }) ,
        'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
            return [
                'type' => [
                    'id' => $meeting->type->id ,
                    'name' => $meeting->type->name ,
                ],
                'total' => $meeting->total
            ];
        })
        ];
    });
    }
    /**
     * Get total meetings which lead by each leader and each meeting types within this Year
     */
    public static function totalMeetingsByTypeThisYear($creatorIds=[]){
        $builder = static::whereIn('id',
            // Fetching the people_id from meeting_members table to cut down the number of the records
            \App\Models\Meeting\MeetingMember::selectRaw('people_id')->where('role','leader')->where('group','lead_meeting')->groupby('people_id')->pluck('people_id')
        );
        return $builder->get()->map(function($people) use( $creatorIds ) {
            $start = Carbon::now()->startOfYear();
            $end = $start->copy()->addMonths(11);
            $builder = $people->meetingsJoinedAsLeaderOfLeadMeeting()
            ->whereBetween('official_date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
            if( !empty( $creatorIds ) ) $builder->whereIn('created_by',$creatorIds) ;
            return [
            'totalSpentMinutes' => $builder->get()->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
            'people' => [
                'id' => $people->id ,
                'lastname' => $people->lastname ,
                'firstname' => $people->firstname ,
                'countesies' => $people->countesies ,
                'organizations' => $people->organizations ,
                'positions' => $people->positions
            ] ,
            'total' => $builder->count() ,
            'meetings' => $builder->get()->map(function($record){
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
            }) ,
            'totalMeetingsByTypes' => $builder->selectRaw('type_id, count(type_id) as total')->groupby('type_id','people_id','meeting_id')->get()->map(function($meeting) {
                return [
                    'type' => [
                        'id' => $meeting->type->id ,
                        'name' => $meeting->type->name ,
                    ],
                    'total' => $meeting->total
                ];
            })
            ];
        });
    }
    /**
     * Medal
     */
    public function medals(){
        return $this->hasManyThrough( \App\Models\Officer\Modal::class , \App\Models\Officer\OfficerMedal::class , 'officer_id' , 'medal_id' );
    }
    public function officerMedals(){
        return $this->hasMany( \App\Models\Officer\OfficerMedal::class , 'officer_id' , 'id' );
    }
    public function ranks(){
        return $this->hasManyThrough( \App\Models\Officer\Rank::class , \App\Models\Officer\OfficerRank::class , 'officer_id' , 'rank_id' );
    }
    public function officerRanks(){
        return $this->hasMany( \App\Models\Officer\OfficerRank::class , 'officer_id' , 'id' );
    }
    public function officerJobs(){
        return $this->hasMany( \App\Models\Officer\OfficerJob::class , 'officer_id' , 'id' );
    }
    public function archeivements(){
        return $this->hasMany( \App\Models\Officer\Archeivement::class , 'officer_id' , 'id' );
    }

    /**
     * Reports
     */
    // Get officer counts by additional_officer_type
    public static function getCountByOfficerType()
    {
        return DB::table('officers as o')
            ->join('people as p', 'o.people_id', '=', 'p.id')
            ->select(
                'o.additional_officer_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as percentage')
            )
            ->whereNotNull('o.additional_officer_type')
            ->groupBy('o.additional_officer_type')
            ->orderBy('count', 'DESC')
            ->get();
    }

    // Get detailed statistics by officer type
    public static function getDetailedOfficerTypeStats()
    {
        return DB::select("
            SELECT
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total_officers,
                COUNT(DISTINCT o.organization_id) as organizations_count,
                COUNT(DISTINCT o.position_id) as positions_count,
                COUNT(CASE WHEN o.leader = 1 THEN 1 END) as leaders_count,
                ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, p.dob))), 1) as avg_age,
                COUNT(CASE WHEN p.gender = 0 THEN 1 END) as female_count,
                COUNT(CASE WHEN p.gender = 1 THEN 1 END) as male_count
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            WHERE o.additional_officer_type IS NOT NULL
            GROUP BY o.additional_officer_type
            ORDER BY total_officers DESC
        ");
    }

    // Get officers with missing people records
    public static function getOrphanedOfficers()
    {
        return DB::table('officers as o')
            ->leftJoin('people as p', 'o.people_id', '=', 'p.id')
            ->whereNull('p.id')
            ->select('o.*')
            ->get();
    }

    // Comprehensive report with validation
    public static function getComprehensiveOfficerReport()
    {
        return DB::select("
            WITH officer_data AS (
                SELECT
                    o.additional_officer_type,
                    o.id,
                    o.people_id,
                    o.organization_id,
                    o.position_id,
                    o.leader,
                    p.id as person_exists,
                    p.gender,
                    EXTRACT(YEAR FROM AGE(CURRENT_DATE, p.dob)) as age
                FROM officers o
                LEFT JOIN people p ON o.people_id = p.id
            )
            SELECT
                COALESCE(additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total,
                COUNT(CASE WHEN person_exists IS NOT NULL THEN 1 END) as with_people_record,
                COUNT(CASE WHEN person_exists IS NULL THEN 1 END) as missing_people_record,
                ROUND(COUNT(CASE WHEN person_exists IS NULL THEN 1 END) * 100.0 / COUNT(*), 2) as missing_percentage,
                COUNT(DISTINCT organization_id) as unique_organizations,
                COUNT(CASE WHEN leader = 1 THEN 1 END) as leaders,
                ROUND(AVG(age), 1) as avg_age
            FROM officer_data
            GROUP BY additional_officer_type
            ORDER BY total DESC
        ");
    }
    // Accessor for formatted dates
    public function getUnofficialDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getOfficialDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // Get officer status
    public function getCurrentStatusAttribute()
    {
        if ($this->official_date) {
            return 'Official';
        }

        if ($this->unofficial_date) {
            $internDuration = Carbon::parse($this->unofficial_date)->diffInYears(now());
            if ($internDuration >= 1) {
                return 'Probation Extended';
            }
            return 'Intern (Probation)';
        }

        return 'No Date Recorded';
    }

    // Get internship duration in days
    public function getInternshipDurationAttribute()
    {
        if ($this->unofficial_date && $this->official_date) {
            return Carbon::parse($this->unofficial_date)->diffInDays(
                Carbon::parse($this->official_date)
            );
        }
        return null;
    }

    // Get total years of service
    public function getYearsOfServiceAttribute()
    {
        if ($this->unofficial_date) {
            return Carbon::parse($this->unofficial_date)->diffInYears(now());
        }
        return null;
    }

        // 1. Basic officer type statistics with dates
    public static function getOfficerTypeStatsWithDates()
    {
        return DB::select("
            SELECT
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total_officers,
                COUNT(CASE WHEN o.unofficial_date IS NOT NULL THEN 1 END) as with_intern_date,
                COUNT(CASE WHEN o.official_date IS NOT NULL THEN 1 END) as with_official_date,
                COUNT(CASE WHEN o.official_date IS NOT NULL AND o.unofficial_date IS NOT NULL THEN 1 END) as fully_documented,
                ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, p.dob))), 1) as avg_age,
                COUNT(CASE WHEN p.gender = 0 THEN 1 END) as female_count,
                COUNT(CASE WHEN p.gender = 1 THEN 1 END) as male_count
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            GROUP BY COALESCE(o.additional_officer_type, 'Not Specified')
            ORDER BY total_officers DESC
        ");
    }

    // 2. Internship duration analysis
    public static function getInternshipDurationAnalysis()
    {
        return DB::select("
            WITH officer_service AS (
                SELECT
                    o.id,
                    o.additional_officer_type,
                    o.unofficial_date,
                    o.official_date,
                    CASE
                        WHEN o.unofficial_date IS NOT NULL AND o.official_date IS NOT NULL
                        THEN (TO_DATE(o.official_date, 'YYYY-MM-DD') - TO_DATE(o.unofficial_date, 'YYYY-MM-DD'))
                        ELSE NULL
                    END as internship_days,
                    CASE
                        WHEN o.unofficial_date IS NOT NULL
                        THEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')))
                        ELSE NULL
                    END as years_of_service
                FROM officers o
                INNER JOIN people p ON o.people_id = p.id
            )
            SELECT
                COALESCE(additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total,
                COUNT(CASE WHEN internship_days IS NOT NULL THEN 1 END) as completed_internship,
                ROUND(AVG(internship_days), 0) as avg_internship_days,
                MIN(internship_days) as min_internship_days,
                MAX(internship_days) as max_internship_days,
                ROUND(AVG(years_of_service), 1) as avg_years_service,
                COUNT(CASE WHEN years_of_service >= 5 THEN 1 END) as service_5plus_years,
                COUNT(CASE WHEN years_of_service >= 10 THEN 1 END) as service_10plus_years
            FROM officer_service
            GROUP BY COALESCE(additional_officer_type, 'Not Specified')
            ORDER BY total DESC
        ");
    }

    // 3. Career timeline analysis
    public static function getCareerTimelineAnalysis()
    {
        return DB::select("
            SELECT
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total,
                COUNT(CASE WHEN o.unofficial_date IS NOT NULL THEN 1 END) as with_intern_date,
                COUNT(CASE WHEN o.official_date IS NOT NULL THEN 1 END) as with_official_date,
                ROUND(COUNT(CASE WHEN o.official_date IS NOT NULL THEN 1 END) * 100.0 /
                      NULLIF(COUNT(CASE WHEN o.unofficial_date IS NOT NULL THEN 1 END), 0), 2) as internship_completion_rate,
                COUNT(CASE WHEN EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) = EXTRACT(YEAR FROM CURRENT_DATE) THEN 1 END) as official_this_year,
                COUNT(CASE WHEN EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) = EXTRACT(YEAR FROM CURRENT_DATE) - 1 THEN 1 END) as official_last_year,
                COUNT(CASE
                    WHEN o.unofficial_date IS NOT NULL
                    AND o.official_date IS NULL
                    AND AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')) < INTERVAL '1 year'
                    THEN 1
                END) as interns_in_probation
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            WHERE o.unofficial_date IS NOT NULL OR o.official_date IS NOT NULL
            GROUP BY COALESCE(o.additional_officer_type, 'Not Specified')
            ORDER BY total DESC
        ");
    }

    // 4. Detailed career report with individual officer data
    public static function getDetailedCareerReport()
    {
        return DB::select("
            SELECT
                o.id,
                o.code,
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                CONCAT(p.firstname, ' ', p.lastname) as full_name,
                p.gender,
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, p.dob)) as age,
                o.unofficial_date as intern_start_date,
                o.official_date as official_start_date,
                CASE
                    WHEN o.official_date IS NOT NULL THEN 'Official'
                    WHEN o.unofficial_date IS NOT NULL AND AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')) >= INTERVAL '1 year'
                        THEN 'Probation Extended'
                    WHEN o.unofficial_date IS NOT NULL THEN 'Intern (Probation)'
                    ELSE 'No Date Recorded'
                END as current_status,
                CASE
                    WHEN o.unofficial_date IS NOT NULL AND o.official_date IS NOT NULL
                    THEN (TO_DATE(o.official_date, 'YYYY-MM-DD') - TO_DATE(o.unofficial_date, 'YYYY-MM-DD'))
                    ELSE NULL
                END as internship_duration_days,
                CASE
                    WHEN o.unofficial_date IS NOT NULL
                    THEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')))
                    ELSE NULL
                END as total_years_service,
                o.organization_id,
                o.position_id,
                CASE WHEN o.leader = 1 THEN 'Yes' ELSE 'No' END as is_leader
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            WHERE o.unofficial_date IS NOT NULL OR o.official_date IS NOT NULL
            ORDER BY o.official_date DESC NULLS LAST, o.unofficial_date DESC
        ");
    }

    // 5. Monthly appointment trends
    public static function getMonthlyAppointmentTrends($year = null)
    {
        $query = "
            SELECT
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) as year,
                EXTRACT(MONTH FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) as month,
                COUNT(*) as appointments_count,
                STRING_AGG(CONCAT(p.firstname, ' ', p.lastname), ', ') as sample_names
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            WHERE o.official_date IS NOT NULL
        ";

        if ($year) {
            $query .= " AND EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) = :year";
            $bindings = ['year' => $year];
        } else {
            $bindings = [];
        }

        $query .= "
            GROUP BY
                COALESCE(o.additional_officer_type, 'Not Specified'),
                EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')),
                EXTRACT(MONTH FROM TO_DATE(o.official_date, 'YYYY-MM-DD'))
            ORDER BY year DESC, month DESC, officer_type
        ";

        return DB::select($query, $bindings);
    }

    // 6. Officers ready for promotion (completed 1 year probation)
    public static function getOfficersReadyForPromotion()
    {
        return DB::select("
            SELECT
                o.id,
                o.code,
                CONCAT(p.firstname, ' ', p.lastname) as full_name,
                COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                o.unofficial_date as intern_start_date,
                AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')) as probation_duration,
                EXTRACT(DAY FROM AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD'))) as days_in_probation
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            WHERE o.unofficial_date IS NOT NULL
                AND o.official_date IS NULL
                AND AGE(CURRENT_DATE, TO_DATE(o.unofficial_date, 'YYYY-MM-DD')) >= INTERVAL '1 year'
            ORDER BY o.unofficial_date
        ");
    }

    // 7. Comprehensive dashboard data (FIXED VERSION)
    public static function getOfficerDashboardStats()
    {
        return [
            'by_type' => self::getOfficerTypeStatsWithDates(),
            'internship_analysis' => self::getInternshipDurationAnalysis(),
            'career_timeline' => self::getCareerTimelineAnalysis(),
            'ready_for_promotion' => self::getOfficersReadyForPromotion(),
            'monthly_trends' => self::getMonthlyAppointmentTrends(),
            'total_summary' => DB::selectOne("
                SELECT
                    COUNT(*) as total_officers,
                    COUNT(CASE WHEN official_date IS NOT NULL THEN 1 END) as total_official,
                    COUNT(CASE WHEN unofficial_date IS NOT NULL AND official_date IS NULL THEN 1 END) as total_interns,
                    COUNT(CASE WHEN unofficial_date IS NOT NULL AND official_date IS NULL
                        AND AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD')) >= INTERVAL '1 year'
                        THEN 1 END) as overdue_promotions,
                    ROUND(AVG(CASE
                        WHEN unofficial_date IS NOT NULL AND official_date IS NOT NULL
                        THEN (TO_DATE(official_date, 'YYYY-MM-DD') - TO_DATE(unofficial_date, 'YYYY-MM-DD'))
                        ELSE NULL
                    END), 0) as avg_internship_days
                FROM officers o
                WHERE unofficial_date IS NOT NULL OR official_date IS NOT NULL
            ")
        ];
    }

    // 8. Additional: Monthly appointment trends with subquery (cleaner version)
    public static function getMonthlyAppointmentTrendsClean($year = null)
    {
        $query = "
            SELECT
                officer_type,
                year,
                month,
                COUNT(*) as appointments_count,
                STRING_AGG(full_name, ', ') as sample_names
            FROM (
                SELECT
                    COALESCE(o.additional_officer_type, 'Not Specified') as officer_type,
                    EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) as year,
                    EXTRACT(MONTH FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) as month,
                    CONCAT(p.firstname, ' ', p.lastname) as full_name
                FROM officers o
                INNER JOIN people p ON o.people_id = p.id
                WHERE o.official_date IS NOT NULL
        ";

        if ($year) {
            $query .= " AND EXTRACT(YEAR FROM TO_DATE(o.official_date, 'YYYY-MM-DD')) = :year";
            $bindings = ['year' => $year];
        } else {
            $bindings = [];
        }

        $query .= "
            ) AS officer_data
            GROUP BY officer_type, year, month
            ORDER BY year DESC, month DESC, officer_type
        ";

        return DB::select($query, $bindings);
    }
}
