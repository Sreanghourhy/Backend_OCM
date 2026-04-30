<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class GenerateUsersFromPeopleCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:genusers';
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
            if( $people->users->count() <= 0 ){
                echo "NO HAVE => " . $people->id . PHP_EOL ;
                $p = $people->users()->create([
                    'firstname' => $people->firstname,
                    'lastname' => $people->lastname,
                    'name' => $people->lastname . ' ' . $people->firstname ,
                    'username' => $people->enlastname.''.$people->enfirstname,
                    'email' => $people->email,
                    'active' => 1 , // Unactive user
                    'avartar_url' => '' ,
                    'password' => bcrypt('1234567890+1') ,
                    'email_verified_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'people_id' => $people->id
                ]);
                $clientClientRole = \App\Models\Role::where('name','backend')->orWhere('name','backend')->first();
                if( $clientClientRole != null ){
                    $p->assignRole( $clientClientRole );
                }
            }
        }
        echo "FINISHED" ;
        return Command::SUCCESS;
    }
    
}
