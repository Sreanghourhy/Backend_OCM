<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory;
    public function province(){
        return $this->belongsTo( \App\Models\Location\Province::class , 'province_id' , 'id' );
    }
    public function communes(){
        return $this->hasMany( \App\Models\Location\Commune::class , 'district_id' , 'id' );
    }
    public function villages(){
        return $this->hasMany( \App\Models\Location\Village::class , 'district_id' , 'id' );
    }
}
