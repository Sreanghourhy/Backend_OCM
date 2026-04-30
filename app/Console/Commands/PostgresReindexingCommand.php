<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDF;
use Illuminate\Support\Facades\Schema;

class PostgresReindexingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pgsql:reindexing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the autoincrement of each table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Restart all tables with new incremental
         */
        $schemaSequences = \DB::table('information_schema.sequences')->select(['sequence_name', 'sequence_schema'])->orderby('sequence_schema')->get();
        foreach( $schemaSequences as $index => $schemaSequence ){
            // $sequence = \DB::select(
            //     \DB::raw('SELECT last_value FROM '.$schemaSequence->sequence_name.';')
            // );
            $tableName = str_replace( 
                '_id_seq' , 
                '' , 
                // clear all the number
                preg_replace( "/[0-9]/" , '' , $schemaSequence->sequence_name )
            ) ;
            if (Schema::hasTable( $tableName ) ) {
                if( !in_array( $tableName , [ 
                    // 'migrations' , 
                    'oauth_clients' , 'oauth_access_tokens' , 'oauth_auth_codes' , 'oauth_clients' , 'oauth_personal_access_clientsoauth_refresh_tokens' , 'personal_access_tokens' , 'position_has_permissions' , 'position_has_roles'
                ]) ){
                    \DB::statement("SELECT SETVAL( '".$schemaSequence->sequence_name."' , (SELECT MAX(id) FROM ". $tableName .") , true );");
                    echo $index . " : SET SEQUENCE VALUE TO '" . $schemaSequence->sequence_name . PHP_EOL;
                }
            }else{
                echo "THERE IS NO SCHEMA NAME : " . $tableName . PHP_EOL;
            }
        }
        return Command::SUCCESS;
    }
}
