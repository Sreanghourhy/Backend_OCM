<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class GenerateOfficersFromPeopleCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:genofficers';
    // { types } // normal variable
    // { types* } // array variable. input with space
    // { type?* } // array variable but optional with the '?'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read people card user and create officer is need';

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
        foreach ( \App\Models\People\People::orderby('id','asc')->get() as $index => $people){
            if( $people->officers->count() <= 0 ){
                echo "NO HAVE => " . $people->id . PHP_EOL ;
                $officer = $people->officers()->create([
                    'code' => '' ,
                    'people_id' => $people->id ,
                    'date' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'organization_id' => 0 ,
                    'position_id' => 0 ,
                    'rank' => 0
                ]);
                echo 'CREATE OFFICER ' . $people->lastname . ' ' . $people->firstname . PHP_EOL; 
            }
        }
        echo "FINISHED" ;
        return Command::SUCCESS;
    }
    
}
