<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationStructurePositionRolePermission extends Model
{
    use HasFactory , SoftDeletes;

    public function permission(){
        return $this->belongsTo( \App\Models\Permission::class , 'permission_id' , 'id' );
    }
    public function role(){
        return $this->belongsToMany( \App\Models\Permission::class , 'organization_structure_position_role_permissions' , 'organization_structure_position_role_id' , 'permission_id' );
    }
}