<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medal extends Model
{
    use HasFactory, SoftDeletes;
    public function officers(){
        return $this->hasManyThrough( \App\Models\Officer\Officer::class , \App\Models\Officer\OfficerMedal::class , 'medal_id' , 'officer_id' );
    }
    public function officerMedals(){
        return $this->hasMany( \App\Models\Officer\OfficerMedal::class , 'medal_id' , 'id' );
    }
}
