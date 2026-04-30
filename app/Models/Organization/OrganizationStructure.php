<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Officer\OfficerJob;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationStructure extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function adminFocalPeople(){
        return $this->belongsToMany( \App\Models\Officer\Officer::class , 'document_organization_focal_people' , 'organization_structure_id' , 'officer_id' );
    }
    public function organization(){
        return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
    }
    public function structurePositions(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructurePosition::class , 'organization_structure_id' , 'id' );
    }
    public function positions(){
        return $this->hasManyThrough( \App\Models\Position\Position::class , 'organization_structure_positions' , 'organization_structure_id' , 'position_id' );
    }
    public function children(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructure::class , 'pid' , 'id' );
    }
    public function parentNode(){
        return $this->belongsTo( OrganizationStructure::class , 'pid' , 'id' );
    }
    public function rootPosition(){
        return $this->structurePositions()->where('pid',0)->first();
    }
    public function childPositions(){
        return $this->structurePositions()->whereNotIn('pid',[0])->get();
    }
    public function updateNumberOfChilds(){
        $this->update([['total_childs' => \App\Models\Organization\OrganizationStructure::where('tpid','like',$organizationStructure->tpid.':'.$organizationStructure->id.'%')->count() ]]);
    }
    public function getStructure(){
        $organizationStructure = $this->find($this->id);
        $organizationStructure->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by','desc','image','pdf','active'] ); ;
        $organizationStructure->organization->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] ); ;

        $childrenBuilder = self::where('tpid', 'like' , ( strlen( $this->tpid ) > 1 ? $this->tpid .  $this->id . ':' : '0:'. $this->id . ':' ) . '%' );
        // Count the officers that be under of the organization structure directly
        // $organizationStructure->total_jobs_of_parent_position = $organizationStructure->structurePositions()->whereHas('officerJobs')->get()->map(function($organizationStructurePosition){
        //     return [ 'jobs' => $organizationStructurePosition->officerJobs->count() ];
        // })->sum('jobs');

        $organizationStructure->total_jobs_of_parent_position = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery) use( $organizationStructure ) {
            $jobQuery->whereHas('organizationStructurePosition',function($positionQuery)  use( $organizationStructure ){
                $positionQuery->whereIn('organization_structure_id',[ $organizationStructure->id ]);
            });
        })->count();

        // Coount the officers that be under each of the organization under organization structure
        $organizationStructure->total_jobs_of_children_position = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery) use( $organizationStructure , $childrenBuilder ) {
            $jobQuery->whereHas('organizationStructurePosition',function($positionQuery)  use( $childrenBuilder ){
                $positionQuery->whereIn('organization_structure_id',$childrenBuilder->pluck('id'));
            });
        })->count();

        $organizationStructure->total_jobs = $organizationStructure->total_jobs_of_parent_position + $organizationStructure->total_jobs_of_children_position ;

        $organizationStructure->root_position = $organizationStructure->rootPosition();
        if( $organizationStructure->root_position != null ){
            if( $organizationStructure->root_position->position != null ){
                $organizationStructure->root_position->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
            }
            if( $organizationStructure->root_position->officerJobs != null && $organizationStructure->root_position->officerJobs->count() > 0 ){
                $job = $organizationStructure->root_position->officerJobs->first();
                if( $job->officer != null ){
                    $job->officer->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] ) ;
                    if( $job->officer->people != null ){
                        $job->officer->people->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                        if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
                            $organizationStructure->root_position->image = $job->officer->people->image = \Storage::disk('public')->url( $job->officer->people->image );
                        }
                        else if ( $job->officer->people->users != null ){
                            $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
                            if( $user != null && $user->avatar_url != null ){
                                $organizationStructure->root_position->image = $job->officer->people->image = \Storage::disk('public')->url( $user->avatar_url );
                            }
                        }
                    }
                }
                $job->countesy->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            }
        }

        $records = $childrenBuilder->get()->map(function($organizationStructure){

            $organizationStructure->organization ;
            $organizationStructure->total_jobs_of_parent_position = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery) use( $organizationStructure ) {
                $jobQuery->whereHas('organizationStructurePosition',function($positionQuery)  use( $organizationStructure ){
                    $positionQuery->whereIn('organization_structure_id',[ $organizationStructure->id ]);
                });
            })->count();

            $childrenBuilder = self::where('tpid', 'like' , $organizationStructure->tpid .  $organizationStructure->id . ':' . '%' );
            $organizationStructure->total_jobs_of_children_position = \App\Models\Officer\Officer::whereHas('jobs',function($jobQuery) use( $childrenBuilder ) {
                $jobQuery->whereHas('organizationStructurePosition',function($positionQuery)  use( $childrenBuilder ){
                    $positionQuery->whereIn('organization_structure_id',$childrenBuilder->pluck('id'));
                });
            })->count();

            $organizationStructure->total_jobs = $organizationStructure->total_jobs_of_parent_position + $organizationStructure->total_jobs_of_children_position ;

            $organizationStructure->root_position = $organizationStructure->rootPosition();
            if( $organizationStructure->root_position != null ){
                if( $organizationStructure->root_position->position != null ){
                    $organizationStructure->root_position->position->makeHidden(['position_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );;
                }
                if( $organizationStructure->root_position->officerJobs != null && $organizationStructure->root_position->officerJobs->count() > 0 ){
                    $job = $organizationStructure->root_position->officerJobs->first();
                    if( $job->officer != null ){
                        $job->officer->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] ) ;
                        if( $job->officer->people != null ){
                            $job->officer->people->makeHidden(['people_id','organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                            if( strlen( $job->officer->people->image ) && \Storage::disk('public')->exists( $job->officer->people->image ) ) {
                                $organizationStructure->root_position->image = $job->officer->people->image = \Storage::disk('public')->url( $job->officer->people->image );
                            }
                            else if ( $job->officer->people->users != null ){
                                $user = $job->officer->people->users()->whereNotNull('avatar_url')->first();
                                if( $user != null && $user->avatar_url != null ){
                                    $organizationStructure->root_position->image = $job->officer->people->image = \Storage::disk('public')->url( $user->avatar_url );
                                }
                            }
                        }
                    }
                    $job->countesy->makeHidden(['created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
                }
            }

            return $organizationStructure ;
        });

        // $temp = $records ;
        // foreach( $records AS $index => $child ){
        //     $child->total_jobs_of_children_position = $temp->filter(function($record,$key) use($child){
        //         return str_starts_with( $child->tpid , $record->tpid.':'.$record->id );
        //     })->sum('total_jobs');
        //     $child->total_jobs += $child->total_jobs_of_children_position ;
        //     $records[ $index ] = $child ;
        // }
        // $organizationStructure->total_jobs = $records->sum('total_jobs');
        return [
            'record' => $organizationStructure ,
            'records' => $records ,
            'query' => ( strlen( $this->tpid ) > 1 ? $this->tpid .  $this->id . ':' : '0:'. $this->id . ':' ) . '%'
        ];
    }
    public function getOrganizationStructure(){
        $flat_organizations = collect();
        $organizationStructure = $this->find( $this->id );
        $organizationStructure->total_jobs = $organizationStructure->total_jobs_of_children_position = $organizationStructure->total_jobs_of_parent_position = 0 ;
        $organizationStructure->root_position = $organizationStructure->rootPosition();
        if( $organizationStructure->root_position != null ){
            $organizationStructure->root_position = $organizationStructure->root_position->getOrganizationStructurePosition();
            $organizationStructure->total_jobs_of_parent_position = $organizationStructure->root_position->total_jobs_of_children_position + $organizationStructure->root_position->total_jobs_of_parent_position ;

            $organizationStructure->root_position->makeHidden(['job_desp','desp','position_id']);
        }
        if( $organizationStructure->children != null){
            $organizationStructure->children = $organizationStructure->getOrganizationStructureChildren( $organizationStructure->children , $flat_organizations );
            $organizationStructure->total_jobs_of_children_position = $organizationStructure->children->pluck('total_jobs_of_parent_position')->sum() + $organizationStructure->children->pluck('total_jobs_of_children_position')->sum();

            $organizationStructure->children->makeHidden(['job_desp','desp','position_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        }
        $organizationStructure->total_jobs = $organizationStructure->total_jobs_of_children_position + $organizationStructure->total_jobs_of_parent_position ;
        $organizationStructure->organization->makeHidden(['keyname','record_index'] );
        $organizationStructure->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
        return [
            'structure' => $organizationStructure ,
            'root_organization' => [
                'total_jobs' => $organizationStructure->total_jobs ,
                'total_jobs_of_children_position' => $organizationStructure->total_jobs_of_children_position ,
                'total_jobs_of_parent_position' => $organizationStructure->total_jobs_of_parent_position ,
                'root_position' => $organizationStructure->root_position != null ? $organizationStructure->root_position->makeHidden(['children','officerJobs','organizationStructure']) : null ,
                'organization' => $organizationStructure->organization != null ? $organizationStructure->organization->makeHidden(['active','cids','created_at','updated_at','deleted_at','desp','prefix']) : null ,
                'pid' => null , // $organizationStructure->pid ,
                'id' => $organizationStructure->id ,
                'tpid' => $organizationStructure->tpid
            ],
            'children' => $flat_organizations
        ];
    }
    private function getOrganizationStructureChildren($children, &$flat_organizations ){
        $children->map(function( $child ) use( &$flat_organizations ) {
            $child->total_jobs = $child->total_jobs_of_children_position = $child->total_jobs_of_parent_position = 0 ;
            $child->total_jobs = 0 ;
            $child->root_position = $child->rootPosition();
            if( $child->root_position != null ){
                $child->root_position = $child->root_position->getOrganizationStructurePosition();
                $child->total_jobs_of_parent_position = $child->root_position->total_jobs_of_parent_position + $child->root_position->total_jobs_of_children_position ;
            }
            if( $child->children != null ){
                $child->children = $this->getOrganizationStructureChildren( $child->children , $flat_organizations );
                $child->total_jobs_of_children_position = $child->children->pluck('total_jobs_of_parent_position')->sum() + $child->children->pluck('total_jobs_of_children_position')->sum();
            }
            $child->total_jobs = $child->total_jobs_of_children_position + $child->total_jobs_of_parent_position ;
            $child->organization->makeHidden(['keyname','record_index'] );
            $child->makeHidden(['organization_id','cids','active','created_at','updated_at','deleted_at','created_by','updated_by','deleted_by'] );
            $flat_organizations->push([
                'total_jobs' => $child->total_jobs ,
                'total_jobs_of_children_position' => $child->total_jobs_of_children_position ,
                'total_jobs_of_parent_position' => $child->total_jobs_of_parent_position ,
                'root_position' => $child->root_position != null ? $child->root_position->makeHidden(['children','officerJobs','organizationStructure']) : null ,
                'organization' => $child->organization != null ? $child->organization->makeHidden(['active','cids','created_at','updated_at','deleted_at','desp','prefix']) : null ,
                'pid' => $child->pid ,
                'id' => $child->id ,
                'tpid' => $child->tpid
            ]);
            return $child;
        });
        return $children;
    }
}
