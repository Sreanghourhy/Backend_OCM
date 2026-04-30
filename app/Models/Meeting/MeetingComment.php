<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingComment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function meeting(){
        return $this->belongsTo( \App\Models\Meeting\Meeting::class , 'meeting_id', 'id');
    }

    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo( \App\Models\User::class , 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo( \App\Models\User::class , 'updated_by', 'id');
    }

}
