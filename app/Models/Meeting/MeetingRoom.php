<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Regulator\Tag\Room;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingRoom extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = ['id'];

    public function room(){
        return $this->belongsTo( \App\Models\Meeting\Room::class , 'room_id', 'id');
    }
    public function meeting(){
        return $this->belongsTo( \App\Models\Meeting\Meeting::class , 'meeting_id', 'id');
    }
    public function organization(){
        return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id', 'id');
    }
    public function isAvailable(){
        return true ;
    }
    public function duration(){
        list( $startHour , $startMinutes ) = explode( ':' , $this->start );
        $start = \Carbon\Carbon::now();
        $start->hour = $startHour ;
        $start->minute = $startMinutes ;
        $start->second = 0 ;
        list( $endHour , $endMinutes ) = explode( ':' , $this->end );
        $end = $start->copy();
        $end->hour = $endHour ;
        $end->minute = $endMinutes ;
        $end->second = 0 ;
        return $start->diffInMinutes( $end );
    }
}
