<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationIndustry extends Model
{
    use HasFactory;
    public function industry(){
        $this->belongsTo( \App\Models\Organization\Industry::class , 'industry_id' , 'id' );
    }
    public function organization(){
        $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
    }
}
