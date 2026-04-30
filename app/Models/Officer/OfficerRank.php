<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficerRank extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
    public function rank(){
        return $this->belongsTo( \App\Models\Officer\Rank::class , 'rank_id' , 'id' );
    }
    public function countesy(){
        return $this->belongsTo( \App\Models\People\Countesy::class , 'countesy_id' , 'id' );
    }
}
