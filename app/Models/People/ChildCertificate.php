<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildCertificate extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'birth_certificates' ;
    protected $guarded = ['id'];
    
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
    public function weddingCertificate(){
        return $this->belongsTo( \App\Models\People\WeddingCertificate::class , 'wedding_certificate_id' , 'id' );
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
