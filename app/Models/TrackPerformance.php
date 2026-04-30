<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackPerformance {
  use SoftDeletes;
  private static $blockPerformances = [] ;
  public static function start($blockCodeName){
    static::$blockPerformances[ $blockCodeName ]['start'] = static::$blockPerformances[ $blockCodeName ]['end'] = microtime(TRUE);
  }
  public static function end($blockCodeName){
    static::$blockPerformances[ $blockCodeName ]['end'] = microtime(TRUE);
    static::$blockPerformances[ $blockCodeName ]['time'] = static::$blockPerformances[ $blockCodeName ]['end'] - static::$blockPerformances[ $blockCodeName ]['start'] ;
  }
  public static function save(){
    Log::channel('syslog')->info(json_encode( static::$blockPerformances ) . PHP_EOL);
  }
}