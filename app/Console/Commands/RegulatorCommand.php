<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RegulatorCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regulator:backup {types?*}';
    // { types } // normal variable
    // { types* } // array variable. input with space
    // { type?* } // array variable but optional with the '?'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy all the regulator base on it types. And user can specify the folder to store it. the folder name "backup" with specific date is the default folder.';

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
        echo 'PREPARING BACKING UP' . PHP_EOL;
        $regulatorTypeBuilder = \App\Models\Regulator\Tag\Type::where('model','App\Models\Regulator\Tag\Type')->first()->children();
        if( $this->argument('types') != null && is_array( $this->argument( 'types' ) ) ){
            $regulatorTypeBuilder->whereIn('id', $this->argument('types') );
        }
        echo 'THERE ARE/IS '. ( $regulatorTypeBuilder->count() ) .' REGULATOR TYPE(s) WILL BE BACKING UP.' . PHP_EOL;
        /**
         * Create backup folder
         */
        echo "CHECK BACKUP FOLDER." ;
        $folderName = 'backup-' . \Carbon\Carbon::now()->format('Y-m-d-H-i-s');
        if( !\Storage::disk('regulator')->exists( $folderName ) ){
            \Storage::disk('regulator')->makeDirectory( $folderName );
        }
        
        // Succed create folder
        echo "START BACKING UP THE REGULATORS TO FOLDER ( " . $folderName . " ) : " . PHP_EOL ;
        $totalRegulators = 0 ;
        $totalFailedCopies = 0 ;
        $failedIds = [] ;
        $regulatorTypeBuilder->get()->each(function( $regulatorType ) use( $folderName , &$totalRegulators , &$totalFailedCopies , $failedIds ) {
            echo "BACKING UP REGULATOR TYPE " . $regulatorType->id . " : " . $regulatorType->name . " ( " . $regulatorType->regulators->count() . " ) " . PHP_EOL ;
            $totalRegulators += $regulatorType->regulators->count();
            // Code backing up go here
            $regulatorType->regulators()->get()->each(function($regulator, $index) use( $regulatorType , $folderName , &$totalRegulators , &$totalFailedCopies , $failedIds  ) {

                $source = storage_path() . '/data/'. $regulator->pdf ;
                $destination = storage_path() . '/data/'.$folderName.'/'. str_replace( ['regulators','/'] , [''] , $regulator->pdf ) ;

                if( 
                    $regulator->pdf != "" &&
                    file_exists( $source ) &&
                    is_file ( $source )
                 ){
                    // if( copy( $source , $destination ) ){
                    //     echo "+ REGULATOR TYPE : " . $regulatorType->id . " - INDEX : " . $index + 1 . " - REGULATOR ID : " . $regulator->id . " => OK ." . PHP_EOL ;
                    // }else{
                    //     $totalFailedCopies++ ;
                    //     $failedIds[] =  $regulatorType->id ;
                        // echo "+ REGULATOR TYPE : " . $regulatorType->id . " - INDEX : " . $index + 1 . " - REGULATOR ID : " . $regulator->id . " => FALIED ." . PHP_EOL ;
                    // }
                }else{
                    $totalFailedCopies++ ;
                    $failedIds[] =  $regulatorType->id ;
                    // echo " NO PDF FILE." . PHP_EOL;
                }
            });
        });
        echo "TOTAL REGULATOR(s) : " . $totalRegulators . " , FAILED COPIED : " . $totalFailedCopies . ' , SUCCEED : ' . ( $totalRegulators - $totalFailedCopies ) . PHP_EOL ;
        if( $totalFailedCopies > 0 ){
            echo "ID(s) OF REGULATOR THAT FAILED TO COPY : " . PHP_EOL;
            echo implode( ' , ' , $failedIds ) . PHP_EOL;
        }
        return Command::SUCCESS;
    }
}
