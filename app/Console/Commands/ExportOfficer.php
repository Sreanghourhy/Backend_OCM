<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportOfficer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'officer:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export officer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // រដ្ឋលេខាធិការ
        $officers = \App\Models\Officer\Officer::whereHas('jobs',function($q){ $q->whereHas('organizationStructurePosition',function($q){ $q->where('id',26); }); })->get();
        // អនុរដ្ឋលេខាធិការ
        // $officers = \App\Models\Officer\Officer::whereHas('jobs',function($q){ $q->whereHas('organizationStructurePosition',function($q){ $q->where('id',27); }); })->get();

        $logDirectory = storage_path() ;
        $todayLog = 'officers-'.\Carbon\Carbon::now()->format('Ymd').'.csv';
        $handle = false ;
        if( !file_exists( $logDirectory . '/' . $todayLog ) ){
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
            fputcsv($handle, ['no' , 'id','khmer name' , 'english name' , 'email' ] , ',' );
        }else{
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
        }
        foreach( $officers AS $index => $officer ){
            fputcsv($handle, [
                $index + 1 , 
                $officer->id ,
                $officer->people->lastname . ' ' . $officer->people->firstname ,
                $officer->people->enlastname . ' ' . $officer->people->enfirstname ,
                $officer->people->email
            ]);
        }
        fclose($handle);
    }

}
