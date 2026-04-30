<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;
    protected static $columns = [ 
        'regulator' => [
            'system', 'user_id' , 'regulator_id' , 'datetime'
        ],
        'access' => [
            'system', 'user_id' , 'class' , 'func' , 'desp' , 'datetime' , 'http_origin' , 'http_sec_ch_ua_mobile' , 'http_sec_ch_ua_platform' , 'http_user_agent' , 'http_x_forwarded_for' , 'request_uri' , 'remote_addr' , 'request_time_float'
        ]
    ] ;
    public static function access($data=[]){
        $logDirectory = storage_path() . '/logs/access' ;
        $todayLog = 'regulator-'.\Carbon\Carbon::now()->format('Ymd').'.csv';
        $fields = [
            $data['system'] ,
            $data['user_id'] ,
            $data['class'] ,
            $data['func'] ,
            $data['desp'] ,
            \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            $_SERVER['HTTP_ORIGIN'] ?? '' ,
            $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ?? '',
            $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
            $_SERVER['REQUEST_URI'] ?? '',
            $_SERVER['QUERY_STRING'] ?? '',
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['REQUEST_TIME_FLOAT'] ?? '' 
        ];
        $handle = false ;
        if( !file_exists( $logDirectory . '/' . $todayLog ) ){
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
            fputcsv($handle, self::$columns['access'] , ',' );
        }else{
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
        }
        fputcsv($handle, $fields);
        fclose($handle);
    }
    public static function regulator($data=[]){
        $logDirectory = storage_path() . '/logs/regulators' ;
        $todayLog = 'access-'.\Carbon\Carbon::now()->format('Ymd').'.csv';
        $fields = [
            $data['system'] ,
            $data['user_id'] ,
            $data['regulator_id'] ,
            \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ];
        $handle = false ;
        if( !file_exists( $logDirectory . '/' . $todayLog ) ){
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
            fputcsv($handle, self::$columns['regulator'] , ',' );
        }else{
            $handle = fopen( $logDirectory . '/' . $todayLog , "a+"); 
        }
        fputcsv($handle, $fields);
        fclose($handle);
    }
    public static function getRegulator(){
        $logDirectory = storage_path() . '/logs' ;
        $todayLog = 'regulator-'.\Carbon\Carbon::now()->format('Ymd').'.csv';
        if( file_exists( $logDirectory . '/' . $todayLog ) ){
            $row = 1;
            if (($handle = fopen( $logDirectory . '/' . $todayLog , "r")) !== FALSE) {
                
                while(! feof($handle)){
                    print_r(fgetcsv($handle));
                }
                fclose($handle);

                // while (($data = fgetcsv($handle, 50 , ",")) !== FALSE) {
                //     $num = count($data);
                //     echo "<p> $num fields in line $row: <br /></p>\n";
                //     $row++;
                //     for ($c=0; $c < $num; $c++) {
                //         echo $data[$c] . "<br />\n";
                //     }
                // }
                // fclose($handle);
            }
        }
    }
    
}
