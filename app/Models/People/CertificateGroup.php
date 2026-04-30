<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateGroup extends Model
{
    use HasFactory, SoftDeletes;
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
    public function certificates(){
        return $this->hasMany( \App\Models\People\Certificate::class , 'certificate_group_id' , 'id' );
      }
    // Get statistics for this certificate group
    public function getStatistics()
    {
        return [
            'total_certificates' => $this->certificates()->count(),
            'total_officers' => $this->certificates()
                ->whereHas('people.officer')
                ->distinct('people_id')
                ->count('people_id'),
            'latest_certificate' => $this->certificates()
                ->max('end'),
        ];
    }
}
