<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use SoftDeletes;
    /**
     * Backend Roles
     */
    const ROLE_SUPER = 'super' , ROLE_ADMIN = 'admin' , ROLE_BACK = 'backend' ;
    /**
     * Client Roles
     */
    const ROLE_CLIENT = 'client' ;
    /**
     * All Roles
     */
    const ROLES = [
        /**
         * Backend Roles
         */
        'super' => 'super' ,
        'admin' => 'admin' ,
        'backend' => 'backend' ,
        /**
         * Client Roles
         */
        'client' => 'client'
    ];
    protected $fillable = ['name', 'updated_at', 'created_at','guard_name','tag'];
    public function users() : BelongsToMany{
        return $this->belongsToMany( \App\Models\User::class , 'user_role' , 'role_id' , 'user_id' );
    }
    public static function scopeSuper(){
        return self::where([
            'tag' => 'core_service' ,
            'name' => 'super'
        ]);
    }
    public static function scopeAdmin(){
        return self::where([
            'tag' => 'core_service' ,
            'name' => 'admin'
        ]);
    }
    public static function scopeBackend(){
        return self::where([
            'tag' => 'core_service' ,
            'name' => 'backend'
        ]);
    }
    public static function scopeClient(){
        return self::where([
            'tag' => 'client_service' ,
            'name' => 'client'
        ]);
    }
    public static function isSuper($user){
        if( $user->roles != null && $user->roles->count() > 0 ){
            return in_array( self::super()->first()->id , $user->roles->pluck('id')->toArray() ) ? true : false ;
        }
        return false ;
    }
    public static function isAdmin($user){
        if( $user->roles != null && $user->roles->count() > 0 ){
            return in_array( self::admin()->first()->id , $user->roles->pluck('id')->toArray() ) ? true : false ;
        }
        return false ;
    }
    public static function isBackend($user){
        if( $user->roles != null && $user->roles->count() > 0 ){
            return in_array( self::backend()->first()->id , $user->roles->pluck('id')->toArray() ) ? true : false ;
        }
        return false ;
    }
    public static function isClient($user){
        if( $user->roles != null && $user->roles->count() > 0 ){
            return in_array( self::client()->first()->id , $user->roles->pluck('id')->toArray() ) ? true : false ;
        }
        return false ;
    }
    public function permissions(){
        return $this->hasManyThrough( \App\Models\Permission::class , 'role_has_permissions' , 'role_id' , 'permission_id' );
    }
}