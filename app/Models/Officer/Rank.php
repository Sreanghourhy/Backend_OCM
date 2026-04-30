<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use HasFactory , SoftDeletes;
    public function officers(){
        return $this->hasManyThrough( \App\Models\Officer\Officer::class , \App\Models\Officer\OfficerRank::class , 'rank_id' , 'officer_id' );
    }
    public function officerRanks(){
        return $this->hasMany( \App\Models\Officer\OfficerRank::class , 'rank_id' , 'id' );
    }
}
