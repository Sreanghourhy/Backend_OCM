<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeddingCertificate extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function spouse(){
        return $this->belongsTo( \App\Models\People\People::class , 'spouse_id' , 'id' );
    }
    public function birthCertificates(){
        return $this->hasMany( \App\Models\People\BirthCertificate::class , 'wedding_certificate_id' , 'id' );
    }
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
