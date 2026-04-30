<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationPosition extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function position(){
        $this->belongsTo( \App\Models\Position\Position::class , 'position_id' , 'id' );
    }
    public function organization(){
        $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
    }
}
