<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function organizationIndustries(){
        return $this->hasManyThrough( \App\Models\Organization\Organization::class, \App\Models\Organization\OrganizationIndustry::class , 'organization_id' , 'industry_id' );
    }
    public function organizations(){
        return $this->hasMany( \App\Models\Organization\Organization::class  , 'industry_id' , 'id' );
    }
}
