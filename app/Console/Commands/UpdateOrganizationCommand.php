<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UpdateOrganizationCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:officer:organization';
    // { types } // normal variable
    // { types* } // array variable. input with space
    // { type?* } // array variable but optional with the '?'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update organization of the officer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Create a directory for the regulators
         */
        /**
         * Get all the regulator's types
         */
        echo 'PREPARING IMPORTING USERS...' . PHP_EOL;
        foreach( \App\Models\People\People::all() AS $index => $people ){
            if( $people->organizations->count() > 0 ){
                $organization = \App\Models\Organization\Organization::where('name', $people->organizations->first()->name )->first() ;
                $people->officers->each(function($officer) use($organization) {
                    $officer->update([
                        'organization_id' => $organization->id
                    ]);
                });
            }else{
                $people->officers->each(function($officer) {
                    $officer->update([
                        'organization_id' => 2
                    ]);
                });
            }
            if( $people->positions->count() > 0 ){
                $position = \App\Models\People\Position::where('name', $people->positions->first()->name )->first() ;
                $people->officers->each(function($officer) use($position) {
                    $officer->update([
                        'position_id' => $position->id
                    ]);
                });
            }
            else{
                $people->officers->each(function($officer) {
                    $officer->update([
                        'position_id' => 18
                    ]);
                });
            }
        }
        echo "FINISHED" ;
        return Command::SUCCESS;
    }
}
