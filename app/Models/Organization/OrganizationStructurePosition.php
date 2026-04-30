<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationStructurePosition extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function organizationStructure(){
        return $this->belongsTo( \App\Models\Organization\OrganizationStructure::class , 'organization_structure_id' , 'id' );
    }
    public function position(){
        return $this->belongsTo( \App\Models\Position\Position::class , 'position_id' , 'id' );
    }
    public function officerJobs(){
        return $this->hasMany( \App\Models\Officer\OfficerJob::class , 'organization_structure_position_id' , 'id' );
    }
    public function parentNode(){
        return $this->belongsTo( \App\Models\Organization\OrganizationStructurePosition::class , 'pid' , 'id' );
    }
    public function children(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructurePosition::class , 'pid' , 'id' );
    }
    public function permissions(){
        return $this->belongsToMany( \App\Models\Permission::class , \App\Models\Organization\OrganizationStructurePositionPermission::class , 'organization_structure_position_id' , 'permission_id' );
    }
    public function roles(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructurePositionRole::class , 'organisation_structure_position_id' , 'id' );
    }
    public function updateNumberOfPositions(){
        $this->update(['total_jobs' => $this->officerJobs()->count()]);
    }
    public function getStructure(&$jobs){
        return $this->children->map(function( $child ) use( &$jobs )
        {
            $child->position;
            // $child->permissions;
            // $child->roles;
            $child->total_jobs = $child->total_unit_jobs = 0 ;
            if( $child->officerJobs != null ){
                $child->officerJobs = $child->officerJobs->map(function($job) use( &$child ) {
                    $job->countesy;
                    if( $job->officer != null ){
                        if( $job->officer->people != null ){
                            if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
                                $child->image = \Storage::disk('public')->url( $job->officer->people->image );
                            }
                            else if ( $job->officer->people->users != null ){
                                $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
                                if( $user != null && $user->avatar_url != null ){
                                    $child->image = \Storage::disk('public')->url( $user->avatar_url );
                                }
                            }
                        }
                    }
                    return $job;
                });
                $jobs = $jobs->merge( $child->officerJobs );
                $child->total_jobs = $child->total_unit_jobs = $child->officerJobs->count();
            }
            if( $child->children != null ){
                $child->children = $child->getStructure($jobs);
                $child->total_jobs += $child->children->pluck('total_jobs')->sum();
            }
            return $child;
        });
    }
    public function getOrganizationStructurePosition(){
        $organizationStructurePosition = $this->find( $this->id );
        if( $organizationStructurePosition->organizationStructure != null ){
            $organizationStructurePosition->organizationStructure->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            $organizationStructurePosition->organizationStructure->organization;
            $organizationStructurePosition->organizationStructure->organization->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        }
        $organizationStructurePosition->total_jobs = $organizationStructurePosition->total_jobs_of_children_position = $organizationStructurePosition->total_jobs_of_parent_position = 0 ;
        if( $organizationStructurePosition->officerJobs != null ){
            $organizationStructurePosition->officerJobs->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            $organizationStructurePosition->officerJobs = $organizationStructurePosition->officerJobs->map(function($job) use(&$organizationStructurePosition ){
                if( $job->organizationStructurePosition != null ){
                    $job->organizationStructurePosition->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                    $job->organizationStructurePosition->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
                }
                if( $job->officer != null ){
                    $job->officer->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] ) ;
                    if( $job->officer->people != null ){
                        $job->officer->people->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                        if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
                            $organizationStructurePosition->image = $job->officer->people->image = \Storage::disk('public')->url( $job->officer->people->image );
                        }
                        else if ( $job->officer->people->users != null ){
                            $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
                            if( $user != null && $user->avatar_url != null ){
                                $organizationStructurePosition->image = $job->officer->people->image = \Storage::disk('public')->url( $user->avatar_url );
                            }
                        }
                    }
                }
                $job->countesy->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                return $job;
            });
            $organizationStructurePosition->total_jobs_of_parent_position = $organizationStructurePosition->officerJobs->count();
        }
        if( $organizationStructurePosition->children != null ){
            $organizationStructurePosition->children = $organizationStructurePosition->getOrganizationStructurePositionChildren( $organizationStructurePosition->children );
            $organizationStructurePosition->total_jobs_of_children_position = $organizationStructurePosition->children->pluck('total_jobs_of_children_position')->sum() + $organizationStructurePosition->children->pluck('total_jobs_of_parent_position')->sum();

            $organizationStructurePosition->children->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        }
        $organizationStructurePosition->total_jobs = $organizationStructurePosition->total_jobs_of_children_position + $organizationStructurePosition->total_jobs_of_parent_position ;
        $organizationStructurePosition->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
        $organizationStructurePosition->makeHidden(['organization_id','organization_structure_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        return $organizationStructurePosition;
    }
    private function getOrganizationStructurePositionChildren($children){
        $children = $children->map(function( $child ){
            if( $child->organizationStructure != null ){
                $child->organizationStructure->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                $child->organizationStructure->organization;
                $child->organizationStructure->organization->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            }
            $child->total_jobs = $child->total_jobs_of_children_position = $child->total_jobs_of_parent_position = 0 ;
            if( $child->officerJobs != null ){
                $child->officerJobs = $child->officerJobs->map(function($job) use(&$child) {
                    if( $job->countesy != null ) $job->countesy->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                    if( $job->organizationStructurePosition != null ){
                        $job->organizationStructurePosition->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                        $job->organizationStructurePosition->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
                    }
                    if( $job->officer != null ){
                        $job->officer->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] ) ;
                        if( $job->officer->people != null ){
                            $job->officer->people->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                            if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
                                $child->image = $job->officer->people->image = \Storage::disk('public')->url( $job->officer->people->image );
                            }
                            else if ( $job->officer->people->users != null ){
                                $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
                                if( $user != null && $user->avatar_url != null ){
                                    $child->image = $job->officer->people->image = \Storage::disk('public')->url( $user->avatar_url );
                                }
                            }
                        }
                    }
                    return $job;
                });
                $child->officerJobs->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                $child->total_jobs_of_parent_position = $child->officerJobs->count();
            }
            if( $child->children != null ){
                $child->children = $child->getOrganizationStructurePositionChildren( $child->children );
                $child->total_jobs_of_children_position = $child->children->pluck('total_jobs_of_children_position')->sum() + $child->children->pluck('total_jobs_of_parent_position')->sum();

                $child->children->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            }
            $child->total_jobs = $child->total_jobs_of_children_position + $child->total_jobs_of_parent_position ;
            $child->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
            $child->makeHidden(['organization_id','organization_structure_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            return $child;
        })
        ->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        return $children;
    }
    public static function getAllPositions(){
        $positionIds = self::whereNotNull('position_id')
                      ->orderBy('position_id', 'asc')
                      ->pluck('position_id')
                      ->unique()
                      ->values();
        return \App\Models\Position\Position::whereIn('id', $positionIds)->orderBy('id', 'asc')->get();
    }
}
