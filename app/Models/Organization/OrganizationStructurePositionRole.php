<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationStructurePositionRole extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [ 'id' ] ;

    public function organizationStructurePosition(){
        return $this->belongsTo( \App\Models\Organization\OrganizationStructurePosition::class , 'organization_structure_position_id' , 'id' );
    }
    public function permissions(){
        return $this->hasManyThrough( \App\Models\Permission::class , 'organization_structure_position_role_permissions' , 'organization_structure_position_id' , 'permission_id' );
    }
}
