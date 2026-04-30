<?php

namespace App\Models\Attendant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon as Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeslot extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function users(){
        return $this->belongsToMany( \App\Models\User::class,'user_timeslots','timeslot_id','user_id');
    }

    /**
     * Check whether the checktime is in the timeslot
     * @param $checktime
     * $checktime must be the time with format (HH:ii) (2_DIGITS_24HOURS:2_DIGITS_MINUTES)
     * @return false/integer
     * false -> the checktime or start or end is/are null or empty string
     * 1 -> the checktime is smaller than start time
     * 2 -> the checktime is the value from start to end time
     * 4 -> the checktime is bigger than end time
     */
    public function isChecktimeIn( $checktime ){
        $checktime = $checktime != null && $checktime != "" ? Carbon::parse( $checktime ) : false ;
        $start = $this->start != null && $this->start != "" ? Carbon::parse( $this->start ) : false ;
        $end = $this->end != null && $this->end != "" ? Carbon::parse( $this->end ) : false ;
        if( !$checktime || !$start || !$end ) return false ;
        return $checktime->lt( $start )
        // Checktime is smaller than start
            ? 1
            : (
                $checktime->gte( $start ) && $checktime->lte( $end ) 
                // Checktime is the value from start to end
                ? 2 
                // Checktime is bigger than end
                : 4
            );
    }
    
    /**
     * @param $date
     * $date is the date of the attendant to be checked
     * @return boolean/integer
     */
    public function isWorkingDay($date){
        $date = $date != null && $date != "" ? Carbon::parse( $date ) : false ;
        $effectviveDays = $this->effective_day != null && $this->effective_day != "" ? explode( ',' , $this->effective_day ) : false ;
        if( !$date || !$effectviveDays ) return false ;
        return in_array( $date->weekday() , $effectviveDays );
    }

    /**
     * Get the duration of the timeslot
     * @return float
     * The value will be total minutes of the hours
     */
    public function getMinutes(){
        $start = $this->start != null && $this->start != "" ? Carbon::parse( $this->start ) : false ;
        $end = $this->end != null && $this->end != "" ? Carbon::parse( $this->end ) : false ;
        return !$start || !$end ? 0 : $start->diffInMinutes( $end ) ;
    }
    /**
     * Get hours of the timeslot
     */
    public function getHours(){
        return intval( $this->getMinutes() / 60 );
    }
    
    /**
     * Check-in
     * @param $checktime
     * @return boolean/integer
     */
    public function checkIn( $checktime ){
        $checktime = $checktime != null && $checktime != "" ? Carbon::parse( $checktime ) : false ;
        $start = $this->start != null && $this->start != "" ? Carbon::parse( $this->start ) : false ;
        return ( !$checktime || !$start )
            ? false
            : (
                $checktime->lt( $start )
                    // Check-in time is early than start time
                    ? $checktime->diffInMinutes ( $start )
                    : (
                        // Check-in time is late
                        $checktime->gt( $start )
                            ? - ( $checktime->diffInMinutes ( $start ) )
                            // Check-in is just in time
                            : 0
                    )
            )
        ;
    }
    /**
     * Check-out
     * @param $checktime
     * @return boolean/integer
     */
    public function checkOut( $checktime ){
        $checktime = $checktime != null && $checktime != "" ? Carbon::parse( $checktime ) : false ;
        $end = $this->end != null && $this->end != "" ? Carbon::parse( $this->end ) : false ;
        return ( !$checktime || !$end )
            ? false
            : (
                $checktime->lt( $end )
                    // Check-out time is early than end time
                    ? - ( $checktime->diffInMinutes ( $end ) )
                    : (
                        // Check-out time is right after end time
                        $checktime->gt( $end )
                            ? $checktime->diffInMinutes ( $end )
                            // Check-out is just in time
                            : 0
                    )
            )
        ;
    }
    /**
     * Calculate the working hour of a timeslot
     * @param $checkInTime $checkOutTime
     * @return Array
     */
    public function calculateChecktime( $checkTimeIn , $checkTimeOut = null , $date = null ){
        $duration = $this->getMinutes() - intval( $this->rest_duration );
        $workingTime = [
            'checkin_id' => $checkTimeIn->id ,
            'checkout_id' => $checkTimeOut != null ? $checkTimeOut->id : null ,
            'status' => $checkTimeIn->getStatus() ,
            'timeslot' => [
                'id' => $this->id ,
                'title' => $this->title ,
                'start' => $this->start ,
                'end' => $this->end ,
                'effective_day' => $this->effective_day ,
            ],
            'date' => $checkTimeIn->attendant->date ,
            'day_of_week' => \Carbon\Carbon::parse( $checkTimeIn->attendant->date )->dayOfWeek ,
            'checkin' => $checkTimeIn->checktime ,
            'checkout' => $checkTimeOut != null 
                ? $checkTimeOut->checktime 
                // Assign the end time of the timeslot
                : ( $this->end != null ? (
                    \Carbon\Carbon::parse( $date )->isToday()
                    ? null : $this->end
                ) : null ) ,
            'checkingIn' => $this->checkIn( $checkTimeIn->checktime ) ,
            'checkingOut' => $checkTimeOut != null 
                ? $this->checkOut( $checkTimeOut->checktime ) 
                : ( $this->end != null ? (
                    \Carbon\Carbon::parse( $date )->isToday()
                    ? null : $this->checkOut( $this->end )
                ) : null ),
            'lateOrEarly' => 0 ,
            'workedTime' => 0 ,
            'duration' => $duration ,
            'overtime' => 0 
        ];
        $workingTime['lateOrEarly'] = $checkTimeIn != null ? ( floatval( $workingTime['checkingIn'] ) + floatval( $workingTime['checkingOut'] ) ) : floatval( $workingTime['checkingIn'] ) ;
        $workingTime['workedTime'] = $checkTimeIn != null && $checkTimeOut != null ? floatval( $duration ) + floatval( $workingTime['lateOrEarly'] ) : 0 ;
        $workingTime['overtime'] = $checkTimeIn != null ? ( $workingTime['lateOrEarly'] > 0 ? $workingTime['lateOrEarly'] : 0 ) : 0 ;
        return $workingTime;
    }
    public static function getTimeslot($datetime){
        if( strlen( $datetime ) > 0 ){
            $datetime = \Carbon\Carbon::parse( $datetime );
            return static::orderby('start','asc')->get()->filter(function($timeslot, $key) use( $datetime ){
                $start = \Carbon\Carbon::parse( $datetime->format("Y-m-d") . ' ' . $timeslot->start );
                $end = \Carbon\Carbon::parse( $datetime->format("Y-m-d") . ' ' . $timeslot->end );
                return ( $datetime->gte( $start ) && $datetime->lte( $end ) ) ;
            })->first();
        }
        return null ;
    }
    
}
