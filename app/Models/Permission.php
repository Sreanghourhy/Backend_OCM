<?php

namespace App\Models;
use Spatie\Permission\Models\Permission as OriginalPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'updated_at', 'created_at','title'];
    public function roles(){
        return $this->hasManyThrough( \App\Models\Role::class , 'role_has_permissions' , 'permission_id' , 'role_id' );
    }
}
