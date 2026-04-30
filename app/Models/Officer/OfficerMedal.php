<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficerMedal extends Model
{
    use HasFactory, SoftDeletes;
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
    public function medal(){
        return $this->belongsTo( \App\Models\Officer\Medal::class , 'medal_id' , 'id' );
    }
}
