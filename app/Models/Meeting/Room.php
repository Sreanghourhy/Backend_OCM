<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This class is use to identify the type of the regulator
 */
class Room extends Model
{
    use SoftDeletes;
    // public function __construct(){
    //     parent::__construct(get_class($this));
    // }
    protected $guarded = ['id'];
    /**
     * Relationship
     */
    // public function childNodes(){
    //     return $this->hasMany('\App\Models\Meeting\Room','pid','id');
    // }
    public function meetingRooms(){
        return $this->hasMany('\App\Models\Meeting\MeetingRoom','room_id','id');
    }
    public function meetings(){
        return $this->belongsToMany( \App\Models\Meeting\Meeting::class, 'meeting_rooms' , 'room_id' , 'meeting_id');
    }
    public function notStartedMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->where('status',1);
    }
    public function progressMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->whereIn('status',[ 2 ]);
    }
    public function tobeContinuedMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->whereIn('status',[ 4 ]);
    }
    public function changedMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->whereIn('status',[ 8 ]);
    }
    public function delayedMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->whereIn('status',[ 16 ]);
    }
    public function finishedMeetings(){
        // const STATUS_NEW = 1 , STATUS_MEETING = 2 , STATUS_CONTINUE = 4 , STATUS_CHANGE = 8 , STATUS_DELAY = 16 , STATUS_FINISHED = 32 ;
        return $this->meetings()->whereIn('status',[ 32 ]);
    }
}
