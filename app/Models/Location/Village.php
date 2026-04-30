<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Village extends Model
{
    use HasFactory;
    public function province(){
        return $this->belongsTo( \App\Models\Location\Province::class , 'province_id' , 'id' );
    }
    public function district(){
        return $this->belongsTo( \App\Models\Location\District::class , 'district_id' , 'id' );
    }
    public function commune(){
        return $this->belongsTo( \App\Models\Location\Commune::class , 'commune_id' , 'id' );
    }
}
