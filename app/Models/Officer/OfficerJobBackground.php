<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficerJobBackground extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
    public function officerJob(){
        return $this->belongsTo( \App\Models\Officer\OfficerJob::class , 'officer_job_id' , 'id' );
    }
}
