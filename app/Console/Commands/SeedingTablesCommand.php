<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SeedingTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pgsql:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create seeding file from table(s), table_names is array with comma as delimiter.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "CREATE SEED FILE ..." . PHP_EOL;
        echo 'INITIAL MEMORY : ' . memory_get_usage() . ' bytes' . PHP_EOL;

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $schemaSequences = \DB::table('information_schema.sequences')->select(['sequence_name', 'sequence_schema'])->orderby('sequence_schema')->get();
        foreach( $schemaSequences as $index => $schemaSequence ){
            echo 'INDEX  => ' . $index . PHP_EOL ;
            $tableName = str_replace( 
                '_id_seq' , 
                '' , 
                // clear all the number
                preg_replace( "/[0-9]/" , '' , $schemaSequence->sequence_name )
            ) ;
            if (Schema::hasTable( $tableName )  ) {
                $result_from_artisan = null ;
                if( !in_array( $tableName , [ 
                    'migrations' , 'oauth_clients' , 'oauth_access_tokens' , 'oauth_auth_codes' , 'oauth_clients' , 'oauth_personal_access_clientsoauth_refresh_tokens' , 'oauth_personal_access_clients' , 'personal_access_tokens'
                ]) ){
                    if( \DB::table( $tableName )->count() ){
                        echo $index . " : CREATE SEED FILE FROM TABLE " . $tableName . PHP_EOL ;
                        // \Artisan::call('iseed',['tables'=>$tableName ] );
                        // \Artisan::call('iseed' , [$tableName . ' --chunksize=500' ]);
                        \DB::disableQueryLog();
                        $output = new \Symfony\Component\Console\Output\BufferedOutput;
                        \Artisan::call('iseed' , [ 'tables' => $tableName , '--chunksize' => 500 ] , $output );
                        $content = $output->fetch();
                        echo $content . PHP_EOL;
                        echo $index . " : FINISHED SEED TABLE " . $tableName . PHP_EOL ;
                        echo 'MEMORY USED : ' . memory_get_usage() . ' bytes' . PHP_EOL;
                        echo 'PEAK MEMORY : ' . memory_get_peak_usage() . ' bytes' . PHP_EOL;
                    }else{
                        echo $index . " : THERE IS NO RECORD WITHIN " . $tableName . PHP_EOL;
                    }
                }
            }else{
                echo "THERE IS NO TABLE NAME : " . $tableName . PHP_EOL;
            }
        }
        return Command::SUCCESS;
    }
    private function capitalizeTableName($tableName){
        $terms = explode( '_' , $tableName );
        return implode( '' , array_map( function($term ){ return ucfirst( $term ); } , $terms ) ) . 'TableSeeder.php';
    }
}
