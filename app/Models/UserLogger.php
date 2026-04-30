<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLogger
{
    use SoftDeletes;
    /*
     * Get 10 records of user logs
     * If 5 user log files are not found respectively, return what is availabe
     */
    public static function getUserLogs()
    {
        $curDate = 0;
        // create folder for storing user logs
        $path = storage_path() . '/logs/user_logs/';
        $totalLogFiles = sizeof(File::allFiles($path));
        $total = 0;
        $logs = array();
        $fileRead = 0;

        if ($totalLogFiles > 0) {
            while ($total < 10) {
                if($fileRead >= $totalLogFiles) break; // no more file to read, exit

                $logName = 'user-log-' . date('Y-m-d', strtotime($curDate . ' days'));;
                $uri = $path . $logName;

                if (File::exists($uri)) {
                    $fileRead ++;
                    $tmp = json_decode(File::get($uri));

                    foreach($tmp as $v) {
                        array_push($logs, $v);
                        $total++;

                        if ($total >= 10) break;
                    }
                }

                $curDate--;
            }
        }

        return $logs;
    }
}
