<?php

namespace App\Models\Meeting;
use App\Models\Tag\Tag;
use App\Models\Meeting\Meeting;
use App\Models\Meeting\MeetingOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This class is use to identify the type of the regulator
 */
class Organization extends Tag
{
    use SoftDeletes;
    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instances = [];

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct() {}

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone() { }

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     */
    public static function getInstance(): Organization
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
    /**
     * Abstract methods
     */
    protected static function getModel(){
        return self::class;
    }
    protected static function getRoot(){
        return self::where('model',self::class)->first();
    }
    protected static function getTree($nodeId=false){
        $node = intval( $nodeId ) ? self::find( intval($nodeId) ) : [] ;
        if( $node != null && $node->childNodes != null && !$node->childNodes->isEmpty() ){
            $node->childNodes = $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){
                return self::getChilds( $c );
            }) ;
        }
        return $node ;
    }
    private static function getChilds($node){
        if( !$node->childNodes->isEmpty() ){
            return $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){ 
                return self::getChilds( $c );
            });
        }
        return $node ;
    }
    public function childNodes(){
        return $this->hasMany(self::class,'pid','id');
    }
    public function parentNode(){
        return $this->hasOne(self::class,'id','pid');
    }
    /**
     * Get children
     */
    public function children(){
        return $this->hasMany( self::class , 'pid' , 'id' );
    }
    /**
     * Get parent
     */
    public function ancestor(){
        return $this->belongsTo( self::class , 'pid' , 'id' );
    }
    public function totalChildNodesOfAllLevels(){
        return self::where('tpid',"LIKE", $this->tpid . "%" )->count();
    }

    // Relationships
    public function meetings(){
        return $this->belongsToMany( Meeting::class , MeetingOrganization::class , 'organization_id', 'meeting_id' );
    }
    // Static function of the Organization
    public static function getTotalMeetingsByOrganizations($organizationId=false, $creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        if( !empty( $creatorIds ) ) $builder->whereHas('meetings',function($query) use( $creatorIds ) {
            $query->whereIn('created_by',$creatorIds) ;
        }) ;
        if( $organizationId != false && intval( $organizationId ) > 0 ){
            $builder->where('id',$organizationId);
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
    // Static function of the Organization this week
    public static function getTotalMeetingsByOrganizationsThisWeek($creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        $builder->whereHas('meetings',function($query) {
            $today = Carbon::now();
            $query->whereBetween('date', [ $today->startOfWeek()->format('Y-m-d') , $today->endOfWeek()->format('Y-m-d') ] );
        });
        if( !empty( $creatorIds ) ) {
            $builder->whereHas('meetings',function($query) use( $creatorIds ) {
                $query->whereIn('created_by',$creatorIds) ;
            });
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
    // Static function of the Organization this month
    public static function getTotalMeetingsByOrganizationsThisMonth($creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        $builder->whereHas('meetings',function($query) {
            $today = Carbon::now();
            $query->whereBetween('date', [ $today->startOfMonth()->format('Y-m-d') , $today->endOfMonth()->format('Y-m-d') ] );
        });
        if( !empty( $creatorIds ) ) {
            $builder->whereHas('meetings',function($query) use( $creatorIds ) {
                $query->whereIn('created_by',$creatorIds) ;
            });
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
    // Static function of the Organization first term
    public static function getTotalMeetingsByOrganizationsFirstTerm($creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        $builder->whereHas('meetings',function($query) {
            $start = Carbon::now()->startOfYear();
            $end = $start->copy()->addMonths(2);
            $query->whereBetween('date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
        });
        if( !empty( $creatorIds ) ) {
            $builder->whereHas('meetings',function($query) use( $creatorIds ) {
                $query->whereIn('created_by',$creatorIds) ;
            });
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
    // Static function of the Organization first semester
    public static function getTotalMeetingsByOrganizationsFirstSemester($creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        $builder->whereHas('meetings',function($query) {
            $start = Carbon::now()->startOfYear();
            $end = $start->copy()->addMonths(5);
            $query->whereBetween('date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
        });
        if( !empty( $creatorIds ) ) {
            $builder->whereHas('meetings',function($query) use( $creatorIds ) {
                $query->whereIn('created_by',$creatorIds) ;
            });
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
    // Static function of the Organization this year
    public static function getTotalMeetingsByOrganizationsThisYear($creators=[]){
        $builder = static::getInstance()->children()->has('meetings','>',0);
        $builder->whereHas('meetings',function($query) {
            $start = Carbon::now()->startOfYear();
            $end = $start->copy()->addMonths(11);
            $query->whereBetween('date', [ $start->startOfMonth()->format('Y-m-d') , $end->endOfMonth()->format('Y-m-d') ] );
        });
        if( !empty( $creatorIds ) ) {
            $builder->whereHas('meetings',function($query) use( $creatorIds ) {
                $query->whereIn('created_by',$creatorIds) ;
            });
        }
        return $builder->get()->map(function($organization){
            return [
                'id' => $organization->id ,
                'name' => $organization->name ,
                'total' => $organization->meetings->count() ,
                'totalMeetingsByTypes' => $organization
                    ->meetings()
                    ->selectRaw('type_id, count(type_id) as total')
                    ->groupby('type_id','organization_id','meeting_id')
                    ->get()->map(function($meeting){
                    return [
                        'type' => [
                            'id' => $meeting->type->id ,
                            'name' => $meeting->type->name ,
                        ],
                        'total' => $meeting->total
                    ];
                }),
                'totalSpentMinutes' => $organization->meetings->map(function($meeting){ return $meeting->totalSpentMinutes();})->sum() ,
                'meetings' => $organization->meetings->map(function($record){
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
                })
            ];
        });
    }
}
