<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationStructurePositionPermission extends Model
{
    use HasFactory, SoftDeletes;
    public function organizationStructurePosition(){
        return $this->belongsTo( \App\Models\Organization\OrganizationStructurePosition::class , 'organisation_structure_position_id' , 'id' );
    }
    public function permission(){
        return $this->belongsTo( \App\Models\Permission::class , 'permission_id' , 'id' );
    }
}
