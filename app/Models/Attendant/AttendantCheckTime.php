<?php

namespace App\Models\Attendant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendantCheckTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    const CHECK_TYPE_DEFAULT = 0 , CHECK_TYPE_FACE = 1 , CHECK_TYPE_FINGER = 2 , CHECK_TYPE_PHONE_EMAIL = 4 ;
    const CHECK_STATUS_IN = 1 , CHECK_STATUS_OUT = 0 ;

    public function attendant(){
        return $this->belongsTo( \App\Models\Attendant\Attendant::class , 'attendant_id' , 'id' );
    }
    public function timeslot(){
        return $this->belongsTo( \App\Models\Attendant\Timeslot::class , 'timeslot_id' , 'id' );
    }
    public function checkout(){
        return $this->hasOne( \App\Models\Attendant\AttendantChecktime::class , 'parent_checktime_id' , 'id' );
    }
    public function organization(){
        return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
    }
    /**
     * Check whether this holiday is the sunday
     */
    public function isSunday(){
        return $this->date != null ? Carbon::parse( $this->date )->isSunday() : false ;
    }
    /**
     * Get the attendant status of the checktime
     */
    public function getStatus(){

        if( strtolower( $this->check_status ) == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN ){
            $status = $this->timeslot->checkIn( $this->checktime ) ;
            return $status === false
                ? "N"
                : ( $status === 0 
                    ? \App\Models\Attendant\Attendant::ATTENDANT_PRESENT
                    : (
                        $status < 0
                            ? \App\Models\Attendant\Attendant::ATTENDANT_PRESENT_LATE 
                            : \App\Models\Attendant\Attendant::ATTENDANT_PRESENT_EARLY 
                    )
                );
        }
        return null ;
    }
    public function getTimeSpent(){
        $checkinDate = \Carbon\Carbon::parse( \Carbon\Carbon::parse( $this->created_at )->format( 'Y-m-d' ) . ' ' . $this->checktime );
        $checkoutDate = $this->checkout != null ? \Carbon\Carbon::parse( \Carbon\Carbon::parse( $this->checkout->created_at )->format( 'Y-m-d' ) . ' ' .  $this->checkout->checktime ) : null ;
        return $this->checkout != null ? $checkinDate->diffInMinutes( $checkoutDate ) : 0 ;
    }
    
}
