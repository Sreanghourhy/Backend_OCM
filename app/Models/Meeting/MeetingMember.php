<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MeetingMember extends Model
{
    use HasFactory, SoftDeletes;

    private $meetingMemberRoles = [
        [
          'label' => 'ប្រធាន' ,
          'value' => 'leader'
        ],
        [
          'label' => 'អនុប្រធាន' ,
          'value' => 'deputy_leader'
        ],
        [
          'label' => 'សមាជិក' ,
          'value' => 'member'
        ]
    ];
    private $meetingMemberGroups = [
        [
          'label' => 'អ្នកដឹកនាំប្រជុំ' ,
          'value' => 'lead_meeting'
        ],
        [
          'label' => 'អ្នកការពារ' ,
          'value' => 'defender'
        ],
        [
          'label' => 'អ្នកចូលរួម' ,
          'value' => 'audient'
        ]
    ];
    protected $guarded = ['id'];

    public function meeting(){
        return $this->belongsTo( \App\Models\Meeting\Meeting::class , 'meeting_id', 'id');
    }

    public function member(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id', 'id');
    }

    public function attendant(){
        return $this->hasOne( \App\Models\Meeting\MeetingAttendant::class , 'meeting_member_id', 'id' );
    }
}
