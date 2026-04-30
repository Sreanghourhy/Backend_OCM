<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingOrganization extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function organizations(){
        return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id', 'id');
    }
    public function meeting(){
        return $this->belongsTo( \App\Models\Meeting\Meeting::class , 'meeting_id', 'id');
    }
}
