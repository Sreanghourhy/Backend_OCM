<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory;
    public function districts(){
        return $this->hasMany( \App\Models\Location\District::class , 'province_id' , 'id' );
    }
    public function communes(){
        return $this->hasMany( \App\Models\Location\Commune::class , 'province_id' , 'id' );
    }
    public function villages(){
        return $this->hasMany( \App\Models\Location\Village::class , 'province_id' , 'id' );
    }
}
