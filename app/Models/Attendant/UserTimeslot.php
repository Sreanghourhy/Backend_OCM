<?php

namespace App\Models\Attendant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTimeslot extends Model
{
    use HasFactory, softDeletes;
    public function attendants(){
        return $this->hasMany(\App\Models\Attendant\Attendant::class,'user_timeslot_id','id');
    }
    public function timeslot(){
        return $this->belongsTo( \App\Models\Attendant\Timeslot::class , 'timeslot_id' , 'id');
    }
    public function user(){
        return $this->belongsTo( \App\Models\User::class , 'user_id' , 'id');
    }
}
