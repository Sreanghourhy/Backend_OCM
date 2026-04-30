<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CountOfficer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'org:count-officer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command total officers within each organization and sub organizations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->updateTpidWithColon();
    }

    public function updateTpidWithColon(){
        \App\Models\Organization\OrganizationStructure::all()->map(function($organizationStructure){
            $colons = explode( ":" , $organizationStructure->tpid );
            if( $colons[ count( $colons ) - 1 ] != ':' ){
                $organizationStructure->update(['tpid' => $organizationStructure->tpid . ':' ]);
            }
            $organizationStructure->update(['total_childs' => \App\Models\Organization\OrganizationStructure::where('tpid','like',$organizationStructure->tpid.':'.$organizationStructure->id . ':'.'%')->count() ]);
        });
        \App\Models\Organization\OrganizationStructurePosition::all()->map(function($organizationStructurePosition){
            $colons = explode( ":" , $organizationStructurePosition->tpid );
            if( $colons[ count( $colons) - 1 ] != ':' ){
                $organizationStructurePosition->update(['tpid' => $organizationStructurePosition->tpid . ':' ]);
            }            
            $organizationStructurePosition->update(['total_jobs' => $organizationStructurePosition->officerJobs()->count() ]);
        });
    }

    public function countChildOfOrganizationChart(){
        \App\Models\Organization\OrganizationStructure::all()->map(function($organizationStructure){
            $organizationStructure->update(['total_childs' => \App\Models\Organization\OrganizationStructure::where('tpid','like',$organizationStructure->tpid.':'.$organizationStructure->id.'%')->count() ]);
            return $organizationStructure->total_childs;
        });
    }

    public function countJobsOfEachPositions(){
        \App\Models\Organization\OrganizationStructurePosition::all()->map(function($organizationStructurePosition){
            $organizationStructurePosition->update(['total_jobs' => $organizationStructurePosition->officerJobs()->count() ]);
            return $organizationStructurePosition->total_jobs;
        });
    }

    public function officersWithOrganization(){

        dd(
            // \App\Models\Officer\Officer::doesntHave('jobs')
            \App\Models\Officer\Officer::
            doesntHave('jobs')
            ->orderby('organization_id')
            ->get()->map(function($officer){

                // $organizationStructure = \App\Models\Organization\OrganizationStructure::where('organization_id', $officer->organization_id)
                // ->whereHas('structurePositions',function($query) use($officer) {
                //     $query->where('position_id',$officer->position_id);
                // })->first();
                // if( $officer->jobs->count() <= 0 && $organizationStructure != null ){
                //     $organizationStructurePosition = $organizationStructure->structurePositions()->where('position_id',$officer->position_id)->first() ;
                //     if( $organizationStructurePosition != null ){
                //         $officer->jobs()->create([
                //             'organization_structure_position_id' => $organizationStructurePosition->id ,
                //             'officer_id' => $officer->id ,
                //             'countesy_id' => $officer->countesy_id ,
                //             'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                //             'end' => null ,
                //             'created_by' => 1 ,
                //             'updated_by' => 1 ,
                //             'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                //             'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                //         ]);
                //     }
                // }

                // Make sure the organization_id is not null or 0
                if( intval( $officer->organization_id ) > 0 && $officer->organization != null ){
                    $organizationStructure = \App\Models\Organization\OrganizationStructure::where('organization_id', $officer->organization_id)->first();
                    if( $organizationStructure != null ){
                        // Chech or create new position within this organization
                        if( intval( $officer->position_id ) > 0 && $officer->position != null ){
                            $organizationStructurePosition = $organizationStructure->structurePositions()->where('position_id',$officer->position_id )->first();
                            if( $organizationStructurePosition != null ){
                                // Check whether the officer has already has the current job
                                // if( ( $organizationStructure->organization_id == 437 && $organizationStructurePosition->position_id == 17 ) ){
                                    $job = $officer->jobs()->where('organization_structure_position_id',$organizationStructurePosition->id)->first();
                                    if( $job == null ){
                                        echo 'Create Position : ' . $organizationStructurePosition->position->name . ' in ' . $organizationStructure->organization->name . ' for ' . $officer->code . PHP_EOL;
                                        echo 'Officer details : ' . $officer->id . ' , ' . $officer->code . " -> " . $officer->people->lastname . ' ' . $officer->people->firstname . PHP_EOL;
                                        $job = $officer->jobs()->create([
                                            'organization_structure_position_id' => $organizationStructurePosition->id ,
                                            'officer_id' => $officer->id ,
                                            'countesy_id' => intval( $officer->countesy_id ) > 0 ? $officer->countesy_id : 0 ,
                                            'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                                            'end' => null ,
                                            'created_by' => 1 ,
                                            'updated_by' => 1 ,
                                            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                                            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                                        ]);
                                        echo 'Create Job Successfully => ' . $organizationStructure->organization->name . ' => ' . $organizationStructurePosition->position->name . PHP_EOL;
                                    }else{
                                        echo "This officer has already been in this job => " . $organizationStructure->organiztion->name . ' => ' . $organizationStructurePosition->position->name . PHP_EOL;
                                    }
                                // }
                            }
                            else{
                                echo 'Organization Chart of Organization : ' . $organizationStructure->organization->id . ' : ' . $organizationStructure->organization->name . ' with this Position : ' . $officer->position->id. ' : ' . $officer->position->name . ' => does not exists. should created it.' . PHP_EOL;
                                echo 'Officer details : ' . $officer->id . ' , ' . $officer->code . " -> " . $officer->people->lastname . ' ' . $officer->people->firstname . PHP_EOL;
                            }
                        }
                        else{
                            echo 'Position with this id : ' . $officer->position_id . ' => does not exists ' . PHP_EOL;
                        }
                    }
                    else{
                        echo 'Organization Chart with this Organization : ' . $officer->organization->id . ' : ' . $officer->organization->name . ' => does not exists should created it.' . PHP_EOL;
                        echo 'Officer details : ' . $officer->id . ' , ' . $officer->code . " -> " . $officer->people->lastname . ' ' . $officer->people->firstname . PHP_EOL;
                    }
                }
                else{
                    echo 'Organization with this id : ' . $officer->organization_id . ' => does not exists ' . PHP_EOL;
                }
                // return $officer->id . ',' . $officer->organization_id  . ',' . $officer->position_id ;
            })
            // ->toArray()
            //->count()

        );

        dd(
            [
            'has' => \App\Models\Officer\Officer::whereHas('jobs')->whereNull('deleted_at')->count() ,
            'doesntHave' => \App\Models\Officer\Officer::doesntHave('jobs')->whereNull('deleted_at')->count()
            ]
        );
    }
}
