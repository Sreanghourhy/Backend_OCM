<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingAttendant extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'] ;

    public function member(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id', 'id');
    }
    public function meeting(){
        return $this->belongsTo( \App\Models\Meeting\MeetingMember::class , 'meeting_member_id', 'id');
    }
}
