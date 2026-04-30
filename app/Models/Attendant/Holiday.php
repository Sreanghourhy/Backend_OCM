<?php

namespace App\Models\Attendant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    /**
     * Check whether this holiday is the sunday
     */
    public function isSunday(){
        return $this->date != null ? Carbon::parse( $this->date )->isSunday() : false ;
    }
    public static function isHoliday($date){
        return $date != null ? Carbon::parse( $date ) : false ;
    }
}
