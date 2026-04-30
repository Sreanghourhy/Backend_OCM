<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * Admin Officers
         */
        $filePath = storage_path('adminofficers.xlsx');
        $data = Excel::toArray([],$filePath);
        $data = $data[0];
        $totalOfficersNumber = 0 ;
        $totalActualCount = 0 ;
        $errorOfficerCodes = [];
        if( count( $data ) > 1 ){
            for( $i=1 ; $i < count( $data ) ; $i++ ){
                
                // if( strlen( $data[ $i ][0] ) > 0 ){
                //     $totalOfficersNumber++;
                //     if( ( $officer = \App\Models\Officer\Officer::where('code',trim($data[$i][0]))->first() ) != null ) {
                        
                //         if( $officer->people == null ){
                //             print_r( $data[$i][0] );
                //         }
                //         $totalActualCount++;
                //         if( \App\Models\People\Card::where([
                //             'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
                //             'people_id' => $officer->people->id ,
                //             'officer_id' => $officer->id ,
                //             'start' => '2024-09-01' ,
                //             'end' => '2026-09-01'
                //         ])->first() == null ){
                //             $card = $officer->people->cards()->create([
                //                 'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
                //                 'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $officer->people->id ) ,
                //                 'people_id' => $officer->people->id ,
                //                 'officer_id' => $officer->id ,
                //                 'active' => 1 ,
                //                 'start' => '2024-09-01' ,
                //                 'end' => '2026-09-01' ,
                //                 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                //                 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                //             ]);
                //         }
                //     }
                //     else{
                //         $errorOfficerCodes[] = $data[$i][0] ;
                //     }
                // }

                // if( strlen( $data[ $i ][0] ) <= 0 && strlen( $data[ $i ][1] ) > 0 ){
                //     $totalOfficersNumber++;
                //     if( ( $officer = \App\Models\Officer\Officer::whereHas('people',function($q)use($data,$i){
                //         $q->where('nid',trim($data[$i][1]));
                //     })->first() ) != null ){
                //         if( $officer->people == null ){
                //             print_r( $data[$i][1] );
                //         }
                //         if( \App\Models\People\Card::where([
                //             'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
                //             'people_id' => $officer->people->id ,
                //             'officer_id' => $officer->id ,
                //             'start' => '2024-09-01' ,
                //             'end' => '2026-09-01'
                //         ])->first() == null ){
                //             $card = $officer->people->cards()->create([
                //                 'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
                //                 'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $officer->people->id ) ,
                //                 'people_id' => $officer->people->id ,
                //                 'officer_id' => $officer->id ,
                //                 'active' => 1 ,
                //                 'start' => '2024-09-01' ,
                //                 'end' => '2026-09-01' ,
                //                 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                //                 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                //             ]);
                //         }
                //         $totalActualCount++;
                //     }
                //     else{
                //         $errorOfficerCodes[] = $data[$i][0] . ' - ' . $data[$i][1] ;
                //     }
                // }
                
            }
            echo "Total officer that has id : " . $totalOfficersNumber . PHP_EOL ;
            echo "Total Officer that actually has id : " . $totalActualCount . PHP_EOL ;
            print_r( $errorOfficerCodes );
        }

        /**
         * Political Officers
         */
        // $filePath = storage_path('politicalofficers.xlsx');
        // $data = Excel::toArray([],$filePath);
        // $data = $data[0];
        // $totalOfficersNumber = 0 ;
        // $totalActualCount = 0 ;
        // $errorOfficerCodes = [];
        // if( count( $data ) > 1 ){
        //     for( $i=1 ; $i < count( $data ) ; $i++ ){
                
        //         if( strlen( $data[ $i ][0] ) > 0 ){
        //             $totalOfficersNumber++;
        //             if( ( $officer = \App\Models\Officer\Officer::where('code',trim($data[$i][0]))->first() ) != null ) {
        //                 $totalActualCount++;
        //                 if( $officer->people == null ){
        //                     print_r( $data[$i][0] );
        //                 }
        //                 if( \App\Models\People\Card::where([
        //                     'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                     'people_id' => $officer->people->id ,
        //                     'officer_id' => $officer->id ,
        //                     'start' => '2024-09-01' ,
        //                     'end' => '2026-09-01'
        //                 ])->first() == null ){
        //                     $card = $officer->people->cards()->create([
        //                         'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                         'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $officer->people->id ) ,
        //                         'people_id' => $officer->people->id ,
        //                         'officer_id' => $officer->id ,
        //                         'active' => 1 ,
        //                         'start' => '2024-09-01' ,
        //                         'end' => '2026-09-01' ,
        //                         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //                         'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        //                     ]);
        //                 }
        //             }
        //             else{
        //                 $errorOfficerCodes[] = $data[$i][0] ;
        //             }
        //         }

        //         if( strlen( $data[ $i ][0] ) <= 0 && strlen( $data[ $i ][1] ) > 0 ){
        //             $totalOfficersNumber++;
        //             if( ( $officer = \App\Models\Officer\Officer::whereHas('people',function($q)use($data,$i){
        //                 $q->where('nid',trim($data[$i][1]));
        //             })->first() ) != null ){
        //                 if( $officer->people == null ){
        //                     print_r( $data[$i][1] );
        //                 }
        //                 if( \App\Models\People\Card::where([
        //                     'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                     'people_id' => $officer->people->id ,
        //                     'officer_id' => $officer->id ,
        //                     'start' => '2024-09-01' ,
        //                     'end' => '2026-09-01'
        //                 ])->first() == null ){
        //                     $card = $officer->people->cards()->create([
        //                         'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                         'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $officer->people->id ) ,
        //                         'people_id' => $officer->people->id ,
        //                         'officer_id' => $officer->id ,
        //                         'active' => 1 ,
        //                         'start' => '2024-09-01' ,
        //                         'end' => '2026-09-01' ,
        //                         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //                         'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        //                     ]);
        //                 }
        //                 $totalActualCount++;
        //             }
        //             else{
        //                 $errorOfficerCodes[] = $data[$i][1] ;
        //             }
        //         }

        //         if( strlen( $data[ $i ][0] ) <= 0 && strlen( $data[ $i ][1] ) <= 0 && strlen( $data[ $i ][3] ) > 0 ){
        //             $names = explode( ' ' , $data[$i][3] );
        //             $lastname = $names[0];
        //             if( count( $names ) <= 1 ) {
        //                 $errorOfficerCodes[] = $data[$i][3] ;
        //                 continue;
        //             }
        //             else if( count( $names ) == 2 ){
        //                 $firstname = $names[1];
        //             }
        //             else if( count( $names ) > 2 ){
        //                 unset($names[0]);
        //                 $firstname = implode( ' ' , $names );
        //             }
        //             $totalOfficersNumber++;
        //             if( ( $officer = \App\Models\Officer\Officer::whereHas('people',function($q)use( $lastname , $firstname ){
        //                 $q->where([
        //                     'enlastname' => trim($lastname) ,
        //                     'enfirstname' => trim($firstname)
        //                 ]);
        //             })->first() ) != null ){
        //                 if( $officer->people == null ){
        //                     print_r( $data[$i][3] );
        //                 }
        //                 if( \App\Models\People\Card::where([
        //                     'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                     'people_id' => $officer->people->id ,
        //                     'officer_id' => $officer->id ,
        //                     'start' => '2024-09-01' ,
        //                     'end' => '2026-09-01'
        //                 ])->first() == null ){
        //                     $card = $officer->people->cards()->create([
        //                         'number' => "OCM-". str_pad( $officer->people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //                         'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $officer->people->id ) ,
        //                         'people_id' => $officer->people->id ,
        //                         'officer_id' => $officer->id ,
        //                         'active' => 1 ,
        //                         'start' => '2024-09-01' ,
        //                         'end' => '2026-09-01' ,
        //                         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //                         'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        //                     ]);
        //                 }
        //                 $totalActualCount++;
        //             }
        //             else{
        //                 $errorOfficerCodes[] = $data[$i][3] ;
        //             }
        //         }
                
        //     }
        //     echo "Total officer that has id : " . $totalOfficersNumber . PHP_EOL ;
        //     echo "Total Officer that actually has id : " . $totalActualCount . PHP_EOL ;
        //     print_r( $errorOfficerCodes );
        // }
        
    }
}
